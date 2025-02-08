<?php

namespace App\Http\Controllers\AccrualPostings;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccurialPostings\AccrualPostingStoreRequest;
use App\Models\GeneralLedgers\AccrualPeriod;
use App\Models\GeneralLedgers\AccrualPosting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Validation\ValidationException;

class AccrualPostingController extends Controller
{
    public function index()
    {
        return view('accrual-postings.index', [
            //
        ]);
    }

    public function create()
    {
        $latest = AccrualPosting::where('company_id', auth()->user()->company_id)->latest()->withTrashed()->first();
        $count = $latest ? $latest->id + 1 : 1 ;
        $id = 'accl-'. str_pad( $count ?? 1, 4, '0', STR_PAD_LEFT);

        return view('accrual-postings.create', [
            'id' => $id,
        ]);
    }

    public function store(AccrualPostingStoreRequest $request)
    {
        $item = AccrualPosting::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        if($item->calendar_type === 'Fiscal') {
            $this->duplicateFiscalPeriod($item, $item->ledger->ledger_calendar->fiscal_calendar->fiscal_periods);
        }else {
            $this->generateAccrualPeriod($item, $request);
        }

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = AccrualPosting::withTrashed()->findOrFail($id);

        return view('accrual-postings.show', [
            'item' => $item,
        ]);
    }

    public function update(AccrualPostingStoreRequest $request, $id)
    {
        $item = AccrualPosting::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->accrual_posting}";

        $item = AccrualPosting::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = AccrualPosting::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->accrual_posting}",
        ]);
    }

    public function restore($id)
    {
        $item = AccrualPosting::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->accrual_posting}",
        ]);
    }

    public function duplicateFiscalPeriod($item, $periods) {
        
        /**
         * If condition for empty variable
         */
        if($periods) {
            $request = [];
            foreach ($periods as $key => $period) {
                $request = $period;
                $request['client_id'] = $item->client_id;
                $request['period_id'] = $period->fiscal_period_id;
                $request['accrual_id'] = $item->id;
                $period =  AccrualPeriod::store($request);
            }
        }else {
            throw ValidationException::withMessages(['Empty Periods' => 'Fiscal Calendar duplication failed']);
        }

    }

    public function generateAccrualPeriod($item, $request) 
    {

        $period = CarbonPeriod::create($request->period_start, '1 month', $request->period_end);
        
        $period_count = $period->count();
        $missing_month = 12 - $period_count;
        $request['fiscal_year_start_date'] = $request->period_start;
        $request['fiscal_year_end_date'] = $request->period_end;

        $count_period = AccrualPeriod::withTrashed()->count() + 1;
        $period_id = str_pad($count_period, 4, '0', STR_PAD_LEFT);
        $request['fiscal_calendar_id'] = $item->fiscal_calendar_id;
        $request['period_id'] = 'ACRDPRD-'. $period_id;
        $request['fiscal_calendar_code'] = $item->fiscal_calendar_code;
        $request['fiscal_period_code'] = 'FSCLPRD-'. $period_id;
        $request['fiscal_period_name'] = 'Period 1';
        $request['fiscal_period_start_date'] = $request->fiscal_year_start_date;
        $request['fiscal_period_end_date'] = $request->fiscal_year_start_date;
        $request['fiscal_quarter'] = Carbon::parse($request->fiscal_year_start_date)->quarter;
        $request['fiscal_month'] = Carbon::parse($request->fiscal_year_start_date)->format('F');
        $request['fiscal_period_status'] = 'Open';  
        $request['fiscal_period_type'] = 'Opening'; 
        $request['accrual_id'] = $item->id; 
        AccrualPeriod::store($request);

        foreach ($period as $key => $date) {
            $count_period = AccrualPeriod::withTrashed()->count() + 1;
            $period_id = str_pad($count_period, 4, '0', STR_PAD_LEFT);
            $request['fiscal_calendar_id'] = $item->fiscal_calendar_id;
            $request['period_id'] = 'ACRDPRD-'. $period_id;
            $request['fiscal_calendar_code'] = $item->fiscal_calendar_code;
            $request['fiscal_year_start_date'] = $date->format('Y');
            $request['fiscal_period_code'] = 'ACRDPRD-'. $period_id;

            if($request->period_frequency === 'Monthly') {

                $request['fiscal_period_name'] = 'Period '. ($key+2);
                $request['fiscal_period_start_date'] = $date->format('Y-m-d');
                $request['fiscal_period_end_date'] = $date->endOfMonth()->format('Y-m-d');
                $request['fiscal_year_end_date'] = $date->endOfMonth()->format('Y');
                $request['fiscal_quarter'] = $date->quarter;
                $request['fiscal_month'] = $date->format('F');
                $request['fiscal_period_status'] = 'Open';  
                $request['fiscal_period_type'] = 'Operating'; 

                AccrualPeriod::store($request);

            } else {
                $request['fiscal_period_type'] = 'Operating'; 

                if($key % 3 == 0) {
                    $first_day_of_the_quarter = $this->getQuarterDate($date->format('Y-m-d'), $key, $period_count, true, $request);
                    $last_day_of_the_quarter = $this->getQuarterDate($date->format('Y-m-d'), ($key + 2), $period_count, false, $request);

                    $request['fiscal_period_name'] = 'Period '. ($key+1);
                    $request['fiscal_year_end_date'] = $date->endOfMonth()->format('Y');
                    $request['fiscal_period_start_date'] = Carbon::parse($first_day_of_the_quarter);
                    $request['fiscal_period_end_date'] = Carbon::parse($last_day_of_the_quarter);
                    $request['fiscal_quarter'] = $date->quarter;
                    $request['fiscal_month'] = Carbon::parse($first_day_of_the_quarter)->format('F'). ' to '. Carbon::parse($last_day_of_the_quarter)->format('F');
                    $request['fiscal_period_status'] = 'Open'; 

                    AccrualPeriod::store($request);
                }
            }                      
        }

        $count_period = AccrualPeriod::withTrashed()->count() + 1;
        $period_id = str_pad($count_period, 4, '0', STR_PAD_LEFT);
        $request['fiscal_calendar_id'] = $item->fiscal_calendar_id;
        $request['period_id'] = 'ACRDPRD-'. $period_id; 
        $request['fiscal_period_start_date'] = $request->fiscal_period_start_date;
        $request['fiscal_period_end_date'] = $request->fiscal_period_end_date;
        $request['fiscal_quarter'] = Carbon::parse($request->fiscal_period_end_date)->quarter;
        $request['fiscal_month'] = Carbon::parse($request->fiscal_period_end_date)->format('F');
        $request['fiscal_period_status'] = 'Open';  
        $request['fiscal_period_type'] = 'Closing'; 
        AccrualPeriod::store($request);
    }


    public function getQuarterDate($_date, $key, $period_count, $for_first_day = true, $request) 
    {
        $date = Carbon::parse($_date);
        $month = $date->format('n');

        if($month < 4) {

            $str = $for_first_day ? 'first day of january ' : 'last day of march ';
            $date->modify($str . $date->format('Y'));

        } elseif ($month > 3 && $month < 7) {

            $str = $for_first_day ? 'first day of april ' : 'last day of june ';
            $date->modify($str . $date->format('Y'));

        } elseif ($month > 6 && $month < 10) {

            $str = $for_first_day ? 'first day of july ' : 'last day of september ';
            $date->modify($str . $date->format('Y'));

        } elseif ($month > 9) {

            $str = $for_first_day ? 'first day of october ' : 'last day of december ';
            $date->modify($str . $date->format('Y'));

        }

        if($key == 0) {
            $date = $_date;
            if(!$for_first_day) {
                $date = Carbon::parse($_date);
                if($month < 4) {
                    $str = $for_first_day ? 'first day of january ' : 'last day of march ';
                    $date->modify($str . $date->format('Y'));

                } elseif ($month > 3 && $month < 7) {

                    $str = $for_first_day ? 'first day of april ' : 'last day of june ';
                    $date->modify($str . $date->format('Y'));

                } elseif ($month > 6 && $month < 10) {

                    $str = $for_first_day ? 'first day of july ' : 'last day of september ';
                    $date->modify($str . $date->format('Y'));

                } elseif ($month > 9) {

                    $str = $for_first_day ? 'first day of october ' : 'last day of december ';
                    $date->modify($str . $date->format('Y'));

                }
            }
        }

        if($key == $period_count) {
            return $request->_fiscal_year_end_date;
        }

        return $date;
    }

    public function updateStatusVoucher(Request $request,  $id, $status) 
    {   
        $item = AccrualPosting::withTrashed()->findOrFail($id);
        if($item) {   
            if($status == 1) {
                $item->update([
                    'approved_by' => auth()->user()->id,
                    'approved_date' => now(),
                    'rejected_on' => null,
                    'rejected_by' => null,
                ]);
            } else if($status == 0){
                $item->update([
                    'approved_by' => null,
                    'approved_date' => null,
                    'rejected_by' =>  auth()->user()->id,
                    'rejected_on' => now(),
                ]);                
            } 
        }    
        return response()->json([
            'message' => "Successfully accrual  $item->accrual_id  has been successfully approved",
        ]);
    }

}
