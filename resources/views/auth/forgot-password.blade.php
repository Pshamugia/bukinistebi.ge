@extends('layouts.app')

@section('title', 'Login')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container mt-5 col-md-4" style="position: relative; padding-top:10%; padding-bottom:5%;">
    <div class="mb-4 text-sm text-gray-600">
        {{ __('messages.forgot') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <br><br>
            
            <input type="email" id="email" class="form-control" name="email" :value="old('email')" required autofocus aria-describedby="emailHelp" placeholder="Enter email">
             <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn btn-success">
                {{ __('messages.send') }}
            </button>
        </div>
    </form> 
</div>
@endsection