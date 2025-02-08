<?php

namespace App\Http\Controllers\Specifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Specifications\SpecificationStoreRequest;
use App\Models\ProductInventories\Products\Specification;

class SpecificationController extends Controller
{
    public function index()
    {
        return view('specifications.index', [
            //
        ]);
    }

    public function create()
    {
        return view('specifications.create', [
            //
        ]);
    }

    public function store(SpecificationStoreRequest $request)
    {
        $item = Specification::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = Specification::withTrashed()->findOrFail($id);

        return view('specifications.show', [
            'item' => $item,
        ]);
    }

    public function update(SpecificationStoreRequest $request, $id)
    {
        $item = Specification::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->id}";

        $item = Specification::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Specification::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->id}",
        ]);
    }

    public function restore($id)
    {
        $item = Specification::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->id}",
        ]);
    }
}
