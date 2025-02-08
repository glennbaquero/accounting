<?php

namespace App\Http\Controllers\Permissions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Permissions\PermissionCategory;
use App\Models\Users\User;

class PermissionFetchController extends Controller
{
    public function fetch(Request $request, $id)
    {
        $categories = PermissionCategory::with('permissions')->orderBy('id', 'asc')->get();
        $user = User::withTrashed()->findOrFail($id);
        $permission_ids = $user->getAllPermissions()->pluck('id')->toArray();

        return response()->json([
            'categories' => $categories,
            'permission_ids' => $permission_ids,
        ]);
    }
}
