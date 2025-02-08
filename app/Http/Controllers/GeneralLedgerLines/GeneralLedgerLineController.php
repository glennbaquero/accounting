<?php

namespace App\Http\Controllers\GeneralLedgerLines;

use App\Http\Controllers\Controller;

use App\Http\Requests\GeneralLedgers\GeneralLedgerStoreRequest;
use App\Models\GeneralLedgers\GeneralLedgerLine;

class GeneralLedgerController extends Controller
{
    public function index()
    {
        return view('general-ledgers.index', [
        
        ]);
    }

    public function create()
    {
        return view('general-ledgers.create', [
    
        ]);
    }

    public function store(GeneralLedgerStoreRequest $request)
    {
        $item = GeneralLedgerLine::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = GeneralLedgerLine::withTrashed()->findOrFail($id);

        return view('general-ledgers.show', [
            'item' => $item,
        ]);
    }

    public function update(GeneralLedgerStoreRequest $request, $id)
    {
        $item = GeneralLedgerLine::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->id}";

        $item = GeneralLedgerLine::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = GeneralLedgerLine::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->id}",
        ]);
    }

    public function restore($id)
    {
        $item = GeneralLedgerLine::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->id}",
        ]);
    }
}
