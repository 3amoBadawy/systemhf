<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function update(Request $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->update([
            'name' => $request->input('name'),
            'name_ar' => $request->input('name_ar'),
            'description' => $request->input('description'),
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }

        if (! $request->has('permissions')) {
            $role->permissions()->detach();
        }

        return redirect()->route('roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }
}
