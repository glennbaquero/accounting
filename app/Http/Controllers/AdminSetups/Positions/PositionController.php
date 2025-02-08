<?php

namespace App\Http\Controllers\AdminSetups\Positions;

use App\Http\Controllers\Controller;

use App\Http\Requests\AdminSetups\PositionStoreRequest;

use App\Models\AdminSetups\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company = null)
    {
        return view('positions.index', [
            'company' => $company,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company = null)
    {
        return view('positions.create', [
            'company' => $company,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionStoreRequest $request)
    {
        $item = Position::store($request);

        $message = "You have successfully created {$item->name}";
        
        if($request->designated_company) {
            $redirect = $item->withCompanyRenderShowUrl();
        }else {
            $redirect = $item->renderShowUrl();
        }

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Position  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id, $company = null)
    {
        $item = Position::withTrashed()->findOrFail($id);

        return view('positions.show', [
            'item' => $item,
            'company'=> $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Position  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(PositionStoreRequest $request, $id)
    {
        $item = Position::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        $item = Position::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Position::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Position  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Position::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }
}
