<?php

namespace App\Http\Controllers\OpeningTransactions;

use App\Http\Controllers\Controller;

use App\Http\Requests\OpeningTransactions\OpeningTransactionStoreRequest;
use App\Models\GeneralLedgers\OpeningTransaction;

class OpeningTransactionController extends Controller
{
    public function index()
    {
        return view('opening-transactions.index', [
            //
        ]);
    }

    public function create()
    {
        return view('opening-transactions.create', [
            //
        ]);
    }

    public function store(OpeningTransactionStoreRequest $request)
    {
        $item = OpeningTransaction::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = OpeningTransaction::withTrashed()->findOrFail($id);

        return view('opening-transactions.show', [
            'item' => $item,
        ]);
    }

    public function update(OpeningTransactionStoreRequest $request, $id)
    {
        $item = OpeningTransaction::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->id}";

        $item = OpeningTransaction::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = OpeningTransaction::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->id}",
        ]);
    }

    public function restore($id)
    {
        $item = OpeningTransaction::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->id}",
        ]);
    }
}
