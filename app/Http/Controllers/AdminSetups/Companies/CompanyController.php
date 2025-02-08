<?php

namespace App\Http\Controllers\AdminSetups\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Companies\CompanyStoreRequest;
use App\Models\AdminSetups\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return view('companies.index', [
            //
        ]);
    }

    public function create()
    {
        return view('companies.create', [
            //
        ]);
    }

    public function store(CompanyStoreRequest $request)
    {
        $item = Company::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = Company::withTrashed()->findOrFail($id);

        return view('companies.show', [
            'item' => $item,
        ]);
    }

    public function update(CompanyStoreRequest $request, $id)
    {
        $item = Company::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->name}";

        $item = Company::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Company::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->name}",
        ]);
    }

    public function restore($id)
    {
        $item = Company::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->name}",
        ]);
    }
}
