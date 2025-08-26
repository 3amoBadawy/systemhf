<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function update(Request $request, Permission $permission): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$permission->id,
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->input('name'),
            'name_ar' => $request->input('name_ar'),
            'description' => $request->input('description'),
            'module' => $request->input('module'),
        ]);

        return redirect()->route('permissions.index')->with('success', 'تم تحديث الصلاحية بنجاح');
    }
}
