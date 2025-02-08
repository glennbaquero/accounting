<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServiceStoreRequest;
use App\Models\Services\Service;
use App\Models\Users\User;

class ServiceController extends Controller
{
    public function index()
    {
        return view('services.index', [
            'clients' => User::getClients(),
        ]);
    }

    public function create()
    {
        return view('services.create', [
            'service_code' => $this->renderCode(),
        ]);
    }

    public function store(ServiceStoreRequest $request)
    {
        $item = Service::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = Service::withTrashed()->findOrFail($id);

        return view('services.show', [
            'item' => $item,
        ]);
    }

    public function update(ServiceStoreRequest $request, $id)
    {
        $item = Service::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->name}";

        $item = Service::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Service::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->name}",
        ]);
    }

    public function restore($id)
    {
        $item = Service::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->name}",
        ]);
    }

    public function renderCode() {
        $service = Service::where('company_id', auth()->user()->company_id)->latest()->first();
        $id  = $service ? $service->id : 1;

       return 'Service' . '-' . str_pad(($id) ?? 1, 4, '0', STR_PAD_LEFT);
    }
}
