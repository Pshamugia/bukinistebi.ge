<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class SubAdminController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'subadmin'])->get();
        return view('admin.subadmins.index', compact('users'));
    }

    public function create(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'subadmin',
            'admin_permissions' => [],   // ✅ ARRAY, NOT JSON
        ]);

        return back()->with('success', 'Sub-admin created');
    }

    public function update(Request $request, User $user)
    {
        $user->admin_permissions = $request->permissions ?? [];
        $user->save();

        return back()->with('success', 'Permissions updated');
    }


    public function destroy($id)
{
    $user = User::findOrFail($id);

    // Prevent deleting main admin
    if ($user->role === 'admin') {
        return back()->with('error', 'You cannot delete main admin.');
    }

    $user->delete();

    return back()->with('success', 'Sub-admin deleted successfully');
}

}