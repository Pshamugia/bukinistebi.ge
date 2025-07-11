<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentCallbackController;

Route::get('/api/admin-status', function () {
    $isAdminOnline = \App\Models\User::where('role', 'admin')->whereNotNull('last_login_at')->exists();
    return response()->json(['online' => $isAdminOnline]);
});


Route::post("/payments/callback", [PaymentCallbackController::class, "handle"])->name('payment.callback');
Route::post("/payments/callback/bookpay", [PaymentCallbackController::class, "handleBookBought"])->name('payment.callback.book');