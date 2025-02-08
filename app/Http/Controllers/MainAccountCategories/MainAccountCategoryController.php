<?php

namespace App\Http\Controllers\MainAccountCategories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\MainAccountCategories\MainAccountCategoryStoreRequest;

use App\Models\MainAccountCategories\MainAccountCategory;

class MainAccountCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('main-accounts-categories.index', [
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
        return view('main-accounts-categories.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MainAccountCategoryStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MainAccountCategoryStoreRequest $request)
    {

        $count = MainAccountCategory::withTrashed()->count();
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;

        $item = MainAccountCategory::store($request);

        $message = "You have successfully created {$item->main_account_category}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = MainAccountCategory::withTrashed()->findOrFail($id);

        return view('main-accounts-categories.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MainAccountCategoryStoreRequest  $request
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(MainAccountCategoryStoreRequest $request, $id)
    {
        $item = MainAccountCategory::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->main_account_category}";

        $item = MainAccountCategory::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = MainAccountCategory::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->main_account_category}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\MainAccountCategory  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = MainAccountCategory::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->main_account_category}",
        ]);
    }
}
