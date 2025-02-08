<?php

namespace App\Http\Controllers\InterestCalculations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\InterestCalculations\InterestCalculationStoreRequest;
use App\Models\InterestCalculations\InterestCalculation;
use App\Models\AdminSetups\Client;

class InterestCalculationController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('interest-calculations.index', [
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
        return view('interest-calculations.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InterestCalculationStoreRequest $request)
    {
        $item = InterestCalculation::store($request);

        $message = "You have successfully created #{$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InterestCalculation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = InterestCalculation::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('interest-calculations.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InterestCalculation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(InterestCalculationStoreRequest $request, $id)
    {
        $item = InterestCalculation::withTrashed()->findOrFail($id);
        $message = "You have successfully updated #{$item->id}";

        $item = InterestCalculation::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InterestCalculation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = InterestCalculation::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\InterestCalculation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = InterestCalculation::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }
}
