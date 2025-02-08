<?php

namespace App\Http\Controllers\FiscalPeriods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\FiscalPeriods\FiscalPeriodStoreRequest;

use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\FiscalCalendars\FiscalCalendar;

class FiscalPeriodController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('fiscal-periods.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($fiscal_calendar_id)
    {
        if(!$fiscal_calendar_id) {
            return back();
        }

        $fiscal_calendar_id = FiscalCalendar::withTrashed()->where('id', $fiscal_calendar_id)->first();
        

        $fp = FiscalPeriod::all()->last();
        $fp_id = "1000";
        if ($fp) {
            $fp_id = 100 + (int) $fp->fiscal_period_id;
        }

        // dd($fp_id);
        

        return view('fiscal-periods.create', [
            'fp_id' => $fp_id,
            'fiscal_calendar_id' => $fiscal_calendar_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\FiscalPeriodStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FiscalPeriodStoreRequest $request)
    {

        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;        
        $item = FiscalPeriod::store($request);

        $message = "You have successfully created {$item->fiscal_period_code}";
        $redirect = route('fiscal-calendars.show', $item->parent->id);

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FiscalPeriod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = FiscalPeriod::withTrashed()->findOrFail($id);
        $fc_id = FiscalCalendar::withTrashed()->where('fiscal_calendar_id', $item->fiscal_calendar_id)->first();
        
        // $fc_id = FiscalCalendar::withTrashed()->where('id', $item->$fiscal_calendar_id)->first();

        // dd($item);
        return view('fiscal-periods.show', [
            'item' => $item,
            'fiscal_calendar_id' => $fc_id,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\FiscalPeriodStoreRequest  $request
     * @param  \App\FiscalPeriod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(FiscalPeriodStoreRequest $request, $id)
    {
        $item = FiscalPeriod::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->fiscal_period_name}";

        $item = FiscalPeriod::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FiscalPeriod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = FiscalPeriod::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->fiscal_period_name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\FiscalPeriod  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = FiscalPeriod::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->fiscal_period_name}",
        ]);
    }
}
