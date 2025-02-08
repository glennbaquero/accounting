<?php

namespace App\Http\Controllers\InterestAdjustments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\InterestAdjustments\InterestAdjustmentStoreRequest;
use App\Models\InterestAdjustments\InterestAdjustment;
use App\Models\AdminSetups\Client;

class InterestAdjustmentController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('interest-adjustments.index', [
            'clients' => $clients,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('interest-adjustments.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InterestAdjustmentStoreRequest $request)
    {
        $item = InterestAdjustment::store($request);

        $message = "You have successfully created {$item->interest_adjustment_id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InterestAdjustment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = InterestAdjustment::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('interest-adjustments.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InterestAdjustment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(InterestAdjustmentStoreRequest $request, $id)
    {
        $item = InterestAdjustment::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->interest_adjustment_id}";

        $item = InterestAdjustment::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InterestAdjustment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = InterestAdjustment::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->interest_adjustment_id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\InterestAdjustment  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = InterestAdjustment::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->interest_adjustment_id}",
        ]);
    }

    public function action(Request $request, $id, $action)
    {
        $item = InterestAdjustment::withTrashed()->findOrFail($id);

        switch ($action) {
            case 'approve':
                $item->update([
                    'approved_checkbox' => true,
                    'approved_date' => now(),
                    'approved_by' => $request->user()->id,
                ]);
                break;
             case 'waive':
                $item->update([
                    'waived_checkbox' => true,
                    'waived_date' => now(),
                    'waived_by' => $request->user()->id,
                ]);
                break;
            case 'reinstate':
                $item->update([
                    'reinstated_checkbox' => true,
                    'reinstated_date' => now(),
                    'reinstated_by' => $request->user()->id,
                ]);
                break;
            case 'reserve':
                $item->update([
                    'reserved_checkbox' => true,
                    'reserved_date' => now(),
                    'reserved_by' => $request->user()->id,
                ]);
                break;
            case 'post':
                $item->update([
                    'posted_checkbox' => true,
                    'posted_date' => now(),
                    'posted_by' => $request->user()->id,
                ]);
                break;
        }

        return response()->json([
            'message' => "You have successfully {$action} {$item->interest_adjustment_id}",
        ]);
    }
}
