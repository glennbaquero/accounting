<?php

namespace App\Http\Controllers\ProductInventories\Products;

use App\Http\Controllers\Controller;

use App\Http\Requests\Products\ProductStoreRequest;
use App\Models\ProductInventories\Products\Product;
use App\Models\Users\User;

class ProductController  extends Controller
{

    public function index()
    {
        return view('products.index', [
            'clients' => User::getClients(),
        ]);
    }

    public function create()
    {
        return view('products.create', [
            //
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        $item = Product::store($request);

        $message = "You have successfully created {$item->item_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = Product::withTrashed()->findOrFail($id);

        return view('products.show', [
            'item' => $item,
        ]);
    }

    public function update(ProductStoreRequest $request, $id)
    {
        $item = Product::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->item_number}";

        $item = Product::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Product::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->item_number}",
        ]);
    }

    public function restore($id)
    {
        $item = Product::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->item_number}",
        ]);
    }
}
