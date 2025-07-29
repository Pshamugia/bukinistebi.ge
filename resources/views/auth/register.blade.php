@extends('layouts.app')

@section('title', 'Register')
 
@section('content')
<h5 class="section-title"
style="position: relative; margin-bottom:25px; top:30px; padding-bottom:25px; align-items: left;
justify-content: left;">
<strong>
    <i class="bi bi-register"></i> {{ __('messages.register') }}
</strong>
</h5>

 
<div class="container mt-5 col-md-6" style="position:relative; margin-top: -15px !important">
    <!-- Name Errors -->
    @if ($errors->has('name'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i> <!-- Optional icon -->
            {{ $errors->first('name') }} <!-- Show first name error -->
        </div>
    @endif

    <!-- Email Errors -->
    @if ($errors->has('email'))
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle"></i> <!-- Optional icon -->
            {{ $errors->first('email') }} <!-- Show first email error -->
        </div>
    @endif

    <!-- Password Errors -->
    @if ($errors->has('password'))
        <div class="alert alert-danger">
            <i class="bi bi-lock-fill"></i> <!-- Optional icon for password -->
            {{ $errors->first('password') }} <!-- Show first password error -->
        </div>
    @endif

    <!-- Password Confirmation Errors -->
    @if ($errors->has('password_confirmation'))
        <div class="alert alert-danger">
            <i class="bi bi-lock-fill"></i> <!-- Optional icon for password confirmation -->
            {{ $errors->first('password_confirmation') }} <!-- Show first password confirmation error -->
        </div>
    @endif

    <div style="border-radius: 5px; border:1px solid #ccc; padding: 22px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mt-4">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('messages.name')}}" aria-label="Name" name="name" :value="old('name')" required autofocus autocomplete="name">
                </div>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <div class="input-group mb-3">
                    <span class="input-group-text">@</span>
                    <input type="email" class="form-control" placeholder="{{ __('messages.email')}}" aria-label="Email" name="email" :value="old('email')" required autocomplete="username">
                </div>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" placeholder="{{ __('messages.password')}}" aria-label="Password" name="password" required autocomplete="new-password">
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" placeholder="{{ __('messages.confirm')}}" aria-label="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    <span>  {{ __('messages.alreadyRegstered') }} </span>
                </a>

                <button type="submit" class="btn btn-success" style="margin-left:5%">
                    {{ __('messages.register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
