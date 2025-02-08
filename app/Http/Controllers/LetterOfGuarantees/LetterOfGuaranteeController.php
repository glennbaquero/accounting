<?php

namespace App\Http\Controllers\LetterOfGuarantees;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Lettes\LetterOfGuarantee;
use App\Models\AdminSetups\Client;

class LetterOfGuaranteeController extends Controller
{
    public function index()
    {
        return view('letter-of-guarantees.index', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('letter-of-guarantees.create', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = LetterOfGuarantee::store($request);

        $message = "You have successfully created {$item->letter_of_guarantee_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);

        return view('letter-of-guarantees.show', [
            'item' => $item,
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->letter_of_guarantee_number}";

        $item = LetterOfGuarantee::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function liquidate(Request $request, $id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);

        $item->update([
            'liquidated' => true,
            'liquidated_on' => now(),
            'status' => 'Liquidate'
        ]);

        $message = "You have successfully liquidate {$item->letter_of_guarantee_number}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function extend(Request $request, $id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);

        $item->update([
            'extended' => true,
            'extended_on' => now(),
            'status' => 'Extended'
        ]);

        $message = "You have successfully extend {$item->letter_of_guarantee_number}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);

        $item->update([
            'approved_checkbox' => true,
            'approved_date' => now(),
            'approved_by' => auth()->user()->fullname,
            'status' => 'Approved'
        ]);

        $message = "You have successfully approved {$item->letter_of_guarantee_number}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->letter_of_guarantee_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Deposit  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = LetterOfGuarantee::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->letter_of_guarantee_number}",
        ]);
    }
}
