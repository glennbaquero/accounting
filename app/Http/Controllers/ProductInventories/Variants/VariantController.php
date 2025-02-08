<?php

namespace App\Http\Controllers\ProductInventories\Variants;

use App\Http\Controllers\Controller;

use App\Http\Requests\Variants\VariantStoreRequest;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;

class VariantController  extends Controller
{
    public function index()
    {
        return view('variants.index', [
            //
        ]);
    }

    public function create($product)
    {
        $product_details = Product::find($product);
        return view('variants.create', [
            'product' => $product,
            'showUrl' => $product_details->renderShowUrl(),
            'product_details' => $product_details
        ]);
    }

    public function store(VariantStoreRequest $request, $product)
    {
        $request['product_id'] = $product;
        $item = Variant::store($request);

        $message = "You have successfully created {$item->name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = Variant::withTrashed()->findOrFail($id);
        $product_details = Product::find($item->product_id);

        return view('variants.show', [
            'item' => $item,
            'showUrl' => $product_details->renderShowUrl(), 
            'product_details' => $product_details,
        ]);
    }

    public function update(VariantStoreRequest $request, $id)
    {
        $item = Variant::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->item_number}";

        $item = Variant::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Variant::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->item_number}",
        ]);
    }

    public function restore($id)
    {
        $item = Variant::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->item_number}",
        ]);
    }
}
