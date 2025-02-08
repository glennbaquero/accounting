<?php

namespace App\Http\Controllers\AdminSetups\Departments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\AdminSetups\DepartmentStoreRequest;

use App\Models\AdminSetups\Department;

class DepartmentController extends Controller
{
    public function index($company = null)
    {
        return view('departments.index', [
            'company' => $company
        ]);
    }

    public function create($company = null)
    {
        return view('departments.create', [
            'company' => $company 
        ]);
    }

    public function store(DepartmentStoreRequest $request)
    {
        $item = Department::store($request);

        $message = "You have successfully created {$item->name}";

        if($request->designated_company) {
            $redirect = $item->withCompanyRenderShowUrl();
        }else {
            $redirect = $item->renderShowUrl();
        }

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id, $company = null)
    {
        $item = Department::withTrashed()->findOrFail($id);
   
        return view('departments.show', [
            'item' => $item,
            'company' => $company
        ]);
    }

    public function update(DepartmentStoreRequest $request, $id)
    {
        $item = Department::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        $item = Department::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = Department::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    public function restore($id)
    {
        $item = Department::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }
}
