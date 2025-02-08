<?php

namespace App\Http\Controllers\TrialBalances;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrialBalances\TrialBalanceStoreRequest;
use App\Models\TrialBalances\TrialBalance;

class TrialBalanceController extends Controller
{
    public function index()
    {
        return view('trial-balance.index', [
            //
        ]);
    }

    public function create()
    {
        return view('trial-balance.create', [
            //
        ]);
    }

    public function store(TrialBalanceStoreRequest $request)
    {
        $item = TrialBalance::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = TrialBalance::withTrashed()->findOrFail($id);

        return view('trial-balance.show', [
            'item' => $item,
        ]);
    }

    public function update(TrialBalanceStoreRequest $request, $id)
    {
        $item = TrialBalance::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->id}";

        $item = TrialBalance::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = TrialBalance::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->id}",
        ]);
    }

    public function restore($id)
    {
        $item = TrialBalance::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->id}",
        ]);
    }
}
