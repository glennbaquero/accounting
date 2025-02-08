<?php

namespace App\Http\Controllers\ServiceTasks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Services\ServiceTask;
use App\Models\Services\Service;

class ServiceTaskController extends Controller
{

    public function create($service_id)
    {

        return view('service-tasks.create', [
            'service' => Service::withTrashed()->findOrFail($service_id),
        ]);
    }

    public function store(Request $request)
    {
        $item = ServiceTask::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = ServiceTask::withTrashed()->findOrFail($id);
        return view('service-tasks.show', [
            'item' => $item,
            'service' => $item->belongToService,
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = ServiceTask::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->service}";

        $item = ServiceTask::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = ServiceTask::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->service}",
        ]);
    }

    public function restore($id)
    {
        $item = ServiceTask::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->service}",
        ]);
    }
}
