<?php

namespace App\Http\Controllers\InterestNotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\InterestNotes\InterestNoteStoreRequest;
use App\Models\InterestNotes\InterestNote;
use App\Models\AdminSetups\Client;

class InterestNoteController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('interest-notes.index', [
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
        return view('interest-notes.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InterestNoteStoreRequest $request)
    {
        $item = InterestNote::store($request);

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
     * @param  \App\InterestNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = InterestNote::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('interest-notes.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InterestNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(InterestNoteStoreRequest $request, $id)
    {
        $item = InterestNote::withTrashed()->findOrFail($id);
        $message = "You have successfully updated #{$item->id}";

        $item = InterestNote::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InterestNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = InterestNote::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\InterestNote  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = InterestNote::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored #{$item->id}",
        ]);
    }

    public function post(Request $request, $id)
    {
        $item = InterestNote::withTrashed()->findOrFail($id);
        $item->update([
            'posted_checkbox' => true,
            'posted_date' => now(),
            'posted_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => "You have successfully posted #{$item->id}",
        ]);
    }


}
