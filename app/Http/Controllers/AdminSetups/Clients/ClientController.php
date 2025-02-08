<?php

namespace App\Http\Controllers\AdminSetups\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\ClientStoreRequest;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('clients.index', [
            //
        ]);
    }

    public function create()
    {
        return view('clients.create', [
            //
        ]);
    }

    public function store(ClientStoreRequest $request)
    {
        $item = Client::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = Client::withTrashed()->findOrFail($id);

        return view('clients.show', [
            'item' => $item,
        ]);
    }

    public function update(ClientStoreRequest $request, $id)
    {
        $item = Client::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->name}";

        $item = Client::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Client::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->name}",
        ]);
    }

    public function restore($id)
    {
        $item = Client::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->name}",
        ]);
    }

    // client user pivot methods
    public function attachToUser(Request $request, $id) {
        $user = User::find($request->user)->clients()->attach($id);

        return response()->json([
            'message' => "You have successfully added new client",
        ]);
    }

    public function detachToUser(Request $request, $id) {
        $user = User::find($request->user)->clients()->detach($id);

        return response()->json([
            'message' => "You have successfully remove the client",
        ]);
    }


}
