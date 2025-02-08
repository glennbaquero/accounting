<?php

namespace App\Http\Controllers\LedgerCalendars;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\LedgerCalendars\LedgerCalendarStoreRequest;
use App\Models\LedgerCalendars\LedgerCalendar;

class LedgerCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ledger-calendars.index', [
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
        $ledger_calendar = LedgerCalendar::all()->last();
          $ledger_calendar_id = "1000";
          if ($ledger_calendar) {
              $ledger_calendar_id = 100 + (int) $ledger_calendar->ledger_calendar_id;
          }
          return view('ledger-calendars.create', [
              'ledger_calendar_id' => $ledger_calendar_id
       ]);         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\LedgerCalendarStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LedgerCalendarStoreRequest $request)
    {

        $count = LedgerCalendar::withTrashed()->count();
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = LedgerCalendar::store($request);

        $message = "You have successfully created {$item->ledger_code}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ledger  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = LedgerCalendar::withTrashed()->findOrFail($id);

        return view('ledger-calendars.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\LedgerCalendarStoreRequest  $request
     * @param  \App\Ledger  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(LedgerCalendarStoreRequest $request, $id)
    {
        $item = LedgerCalendar::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->ledger_calendar_code}";

        $item = LedgerCalendar::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ledger  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = LedgerCalendar::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->ledger_calendar_code}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Ledger  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = LedgerCalendar::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->ledger_calendar_code}",
        ]);
    }
}
