<?php

namespace App\Http\Controllers\AdminSetups\Admins;

use App\Http\Controllers\Controller;

use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdatePermissionRequest;
use App\Models\Users\User;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-users.index', [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {  
        return view('admin-users.create', [
            'type' => $type,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $item = User::store($request);

        $message = "You have successfully created {$item->renderName()}";
        $redirect = $item->adminRenderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function updatePermissions(UserUpdatePermissionRequest $request, $id)
    {
        $item = User::withTrashed()->findOrFail($id);

        $item->updatePermissions($request);

        $message = 'You have successfully updated the permissions of ' . $item->renderName() . '.';
        $action = 1;

        return response()->json([
            'message' => $message,
            'action' => $action,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id, $type = null)
    {
        $item = User::withTrashed()->findOrFail($id);

        return view('admin-users.show', [
            'item' => $item,
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(UserStoreRequest $request, $id)
    {
        $item = User::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        $item = User::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = User::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\User  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = User::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }
}
