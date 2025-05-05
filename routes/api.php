<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/api/admin-status', function () {
    $isAdminOnline = \App\Models\User::where('role', 'admin')->whereNotNull('last_login_at')->exists();
    return response()->json(['online' => $isAdminOnline]);
});



