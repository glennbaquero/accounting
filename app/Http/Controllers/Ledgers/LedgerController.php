<?php

namespace App\Http\Controllers\Ledgers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Ledgers\LedgerStoreRequest;
use App\Models\GeneralLedgers\GeneralLedger;
use App\Models\Ledgers\Ledger;
use Carbon\Carbon;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ledgers.index', [
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
        $latest = Ledger::where('company_id', auth()->user()->company_id)->latest()->withTrashed()->first();
        $id = $latest ? $latest->id + 1 : 1 ;
        $ledger = str_pad( $id ?? 1, 4, '0', STR_PAD_LEFT);
        
        return view('ledgers.create', [
            'ledger_id' => $ledger
        ]);         
    }

    public function store(LedgerStoreRequest $request)
    {
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = Ledger::store($request);
        
        $this->createGeneraLedger($item->id);

        $message = "You have successfully created {$item->ledger_code}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function createGeneraLedger($id)
    {   
        $params = [];
        $ledger = Ledger::find($id);
        $lc = $ledger->ledger_calendar;
        $fc = $ledger->ledger_calendar->fiscal_calendar;
        $params['client_id'] = $ledger->client_id; 
        $params['name'] = 'General Ledger - ' . $ledger->client->name . ' for the period ' .Carbon::parse($lc->fiscal_year_start_date)->format('m/d/Y'). ' to ' . Carbon::parse($lc->fiscal_year_end_date)->format('m/d/Y') . ' of FY ' . $fc->fiscal_calendar_name;
        $params['period_from'] = $lc->fiscal_year_start_date;
        $params['period_to'] = $lc->fiscal_year_end_date;
        $params['created_by'] = $ledger->created_by;
        $params['ledger_calendar_id'] = $lc->fiscal_calendar->id;
        $params['ledger_id'] = $ledger->id;
        $params['ledger_journal_code'] = $ledger->ledger_code;
        $params['ledger_journal_status'] = $ledger->ledger_status;
        $params['company_id'] = auth()->user()->company_id;
        
        $item = GeneralLedger::create($params);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Ledger  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Ledger::withTrashed()->findOrFail($id);

        return view('ledgers.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\LedgerStoreRequest  $request
     * @param  \App\Ledger  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(LedgerStoreRequest $request, $id)
    {
        $item = Ledger::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->ledger_code}";

        $item = Ledger::store($request, $item);

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
        $item = Ledger::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->ledger_code}",
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
        $item = Ledger::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->ledger_code}",
        ]);
    }
}
