<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // Assuming you have an Order model

class PurchaseHistoryController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch orders related to the user
        $orders = Order::where('user_id', $user->id)->get();

        // Pass the orders to the view
        return view('purchase-history', compact('orders'));
    }
}