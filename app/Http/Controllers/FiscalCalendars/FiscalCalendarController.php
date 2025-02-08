<?php

namespace App\Http\Controllers\FiscalCalendars;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\FiscalCalendars\FiscalCalendarStoreRequest;

use App\Models\FiscalCalendars\FiscalCalendar;
use App\Models\LedgerSetup\ChartOfAccount;
use App\Models\FiscalPeriods\FiscalPeriod;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use DB;

class FiscalCalendarController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fiscal-calendars.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fc = FiscalCalendar::withTrashed()->count();
        $fc_id = "1000";

        if ($fc) {
            $fc_id = 1000 + (int) $fc;
        }

        return view('fiscal-calendars.create', [
            'fc_id' => $fc_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\FiscalCalendarStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FiscalCalendarStoreRequest $request)
    {

        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;   
        $request['_fiscal_year_end_date'] = $request->fiscal_year_end_date;

        DB::beginTransaction();
            $item = FiscalCalendar::store($request);

            $this->generateFiscalPeriod($item, $request);

        DB::commit();

        $message = "You have successfully created {$item->fiscal_calendar_code}";
        $redirect = route('fiscal-calendars.show', $item->id);

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
            
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FiscalCalendar  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = FiscalCalendar::withTrashed()->findOrFail($id);
        return view('fiscal-calendars.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\FiscalCalendarStoreRequest  $request
     * @param  \App\FiscalCalendar  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(FiscalCalendarStoreRequest $request, $id)
    {
        $item = FiscalCalendar::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->fiscal_calendar_code}";
        $request['_fiscal_year_end_date'] = $request->fiscal_year_end_date;

        DB::beginTransaction();
            $item = FiscalCalendar::store($request, $item);
            $item->fiscal_periods()->forceDelete();


            $request['created_by'] = auth()->user()->id;
            $request['updated_by'] = auth()->user()->id;   

            $this->generateFiscalPeriod($item, $request);

        DB::commit();

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FiscalCalendar  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = FiscalCalendar::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->fiscal_calendar_code}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\FiscalCalendar  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = FiscalCalendar::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->fiscal_calendar_code}",
        ]);
    }

    public function generateFiscalPeriod($item, $request) 
    {
        $month = $request->length_of_period.' month';

        if($request->unit != 'Months') {
            $month = '1 month';
        }

        $period = CarbonPeriod::create($request->fiscal_year_start_date, $month, $request->fiscal_year_end_date);

        $period_count = $period->count();
        $missing_month = 12 - $period_count;
        $request['_fiscal_year_start_date'] = $request->fiscal_year_start_date;
        $request['_fiscal_year_end_date'] = $request->fiscal_year_end_date;

        $count_period = FiscalPeriod::withTrashed()->count();
        $period_id = str_pad($count_period, 4, '0', STR_PAD_LEFT);
        $request['fiscal_calendar_id'] = $item->fiscal_calendar_id;
        $request['fiscal_period_id'] = 'FSCLPRD-'. $period_id;
        $request['fiscal_calendar_code'] = $item->fiscal_calendar_code;
        $request['fiscal_year_start_date'] = Carbon::parse($request->_fiscal_year_start_date)->format('Y');
        $request['fiscal_period_code'] = 'FSCLPRD-'. $period_id;
        $request['fiscal_period_name'] = 'Period 1';
        $request['fiscal_period_start_date'] = $request->_fiscal_year_start_date;
        $request['fiscal_period_end_date'] = $request->_fiscal_year_start_date;
        $request['fiscal_quarter'] = Carbon::parse($request->_fiscal_year_start_date)->quarter;
        $request['fiscal_month'] = Carbon::parse($request->_fiscal_year_start_date)->format('F');
        $request['fiscal_period_status'] = 'Open';  
        $request['fiscal_period_type'] = 'Opening'; 
        FiscalPeriod::store($request);


        foreach ($period as $key => $date) {
            $count_period = FiscalPeriod::withTrashed()->count();
            $period_id = str_pad($count_period, 4, '0', STR_PAD_LEFT);
            $request['fiscal_calendar_id'] = $item->fiscal_calendar_id;
            $request['fiscal_period_id'] = 'FSCLPRD-'. $period_id;
            $request['fiscal_calendar_code'] = $item->fiscal_calendar_code;
            $request['fiscal_year_start_date'] = $date->format('Y');
            $request['fiscal_period_code'] = 'FSCLPRD-'. $period_id;


            if($request->unit === 'Months') {

                $request['fiscal_period_name'] = 'Period '. ($key+2);
                $request['fiscal_period_start_date'] = $date->format('Y-m-d');
                $request['fiscal_month'] = $date->format('F');
                $request['fiscal_period_end_date'] = $date->addMonths($request->length_of_period - 1)->endOfMonth()->format('Y-m-d');
                $request['fiscal_year_end_date'] = $date->endOfMonth()->format('Y');
                $request['fiscal_quarter'] = $date->quarter;
                $request['fiscal_period_status'] = 'Open';  
                $request['fiscal_period_type'] = 'Operating'; 

                FiscalPeriod::store($request);

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

                    FiscalPeriod::store($request);

                }

            }                      
        }

        $count_period = FiscalPeriod::withTrashed()->count();
        $period_id = str_pad($count_period, 4, '0', STR_PAD_LEFT);
        $request['fiscal_calendar_id'] = $item->fiscal_calendar_id;
        $request['fiscal_period_id'] = 'FSCLPRD-'. $period_id;
        $request['fiscal_calendar_code'] = $item->fiscal_calendar_code;
        $request['fiscal_year_start_date'] = Carbon::parse($request->_fiscal_year_end_date)->format('Y');
        $request['fiscal_period_code'] = 'FSCLPRD-'. $period_id;
        $request['fiscal_period_name'] = 'Period 1';
        $request['fiscal_period_start_date'] = $request->_fiscal_year_end_date;
        $request['fiscal_period_end_date'] = $request->_fiscal_year_end_date;
        $request['fiscal_quarter'] = Carbon::parse($request->_fiscal_year_end_date)->quarter;
        $request['fiscal_month'] = Carbon::parse($request->_fiscal_year_end_date)->format('F');
        $request['fiscal_period_status'] = 'Open';  
        $request['fiscal_period_type'] = 'Closing'; 
        FiscalPeriod::store($request);
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
}