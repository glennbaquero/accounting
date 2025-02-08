<?php

namespace App\Http\Controllers\LedgerSetups\DocumentCodeControls;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentCodeControls\DocumentCodeControlStoreRequest;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use Illuminate\Http\Request;

class DocumentCodeControlController extends Controller
{
    public function index()
    {
        return view('document-code-controls.index', [
            //
        ]);
    }

    public function create()
    {
        return view('document-code-controls.create', [
            //
        ]);
    }

    public function store(DocumentCodeControlStoreRequest $request)
    {
        $item = DocumentCodeControl::store($request);

        $message = "You have successfully created # {$item->id}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    public function show($id)
    {
        $item = DocumentCodeControl::withTrashed()->findOrFail($id);

        return view('document-code-controls.show', [
            'item' => $item,
        ]);
    }

    public function update(DocumentCodeControlStoreRequest $request, $id)
    {
        $item = DocumentCodeControl::withTrashed()->findOrFail($id);
        $message = "You have successfully updated # {$item->name}";

        $item = DocumentCodeControl::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function setActive(Request $request)
    {
            $item = DocumentCodeControl::withTrashed()->findOrFail($request->id);
            $message = "You have successfully updated # {$item->name}";
            $all = DocumentCodeControl::where('client_id', $item->client_id)->where('module_id', $item->module_id)->update(['active' => false]);
            $item->active = true;
            $item->save();

            return response()->json([
                'message' => $message,
            ]);
    }

    public function setInactive(Request $request)
    {
        $item = DocumentCodeControl::withTrashed()->findOrFail($request->id);
        $message = "You have successfully updated # {$item->name}";
        $item->active = false;
        $item->save();

        return response()->json([
            'message' => $message,
        ]);
    }

    public function archive($id)
    {
        $item = DocumentCodeControl::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived # {$item->name}",
        ]);
    }

    public function restore($id)
    {
            $item = DocumentCodeControl::withTrashed()->findOrFail($id);
            $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored # {$item->name}",
        ]);
    }
}
