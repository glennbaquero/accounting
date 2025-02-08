<?php

namespace App\Http\Controllers\ProductInventories\InventoryOnHand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Http\Requests\Variants\VariantUpdateRequest;
use App\Models\ProductInventories\Products\Variant;
use App\Models\Users\User;
use App\Models\Inventories\InventoryOnHand;

class InventoryOnHandController  extends Controller
{

    public function index()
    {
        return view('inventory-on-hands.index', [
            'clients' => User::getClients(),
        ]);
    }

    public function create()
    {
        return view('inventory-on-hands.create', [
            //
        ]);
    }

    public function store(Request $request)
    {
        $item = InventoryOnHand::store($request);

        $message = "You have successfully created {$item->inventory_on_hand_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = InventoryOnHand::withTrashed()->findOrFail($id);

        return view('inventory-on-hands.show', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = InventoryOnHand::withTrashed()->findOrFail($id);
        $item = InventoryOnHand::store($request, $item);

        $message = "You have successfully updated {$item->inventory_on_hand_number}";
        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = InventoryOnHand::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->inventory_on_hand_number}",
        ]);
    }

    public function restore($id)
    {
        $item = InventoryOnHand::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->inventory_on_hand_number}",
        ]);
    }

}
