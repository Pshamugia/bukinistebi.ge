<?php

namespace App\Http\Controllers\Publisher;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PublisherAccountController extends Controller
{
    public function edit()
{
    $publisher = Auth::user(); // or however you retrieve the publisher
    $isHomePage = false;
    return view('publisher.account.edit', compact('publisher', 'isHomePage'));
}




    public function update(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => ['required', 'digits:9'],
            'iban' => 'nullable|string|max:34',
        ]);

        $publisher = Auth::user();
        $publisher->name = $request->name;
        $publisher->email = $request->email;
        $publisher->address = $request->address;
        
        $publisher->phone = $request->phone;
        $publisher->iban = $request->iban; // ✅ Save IBAN


        if ($request->filled('password')) {
            $publisher->password = Hash::make($request->password);
        }

        $publisher->save();

        

        return redirect()->route('publisher.dashboard')->with('success', 'პროფილი წარმატებით განახლდა.');
    }
}
