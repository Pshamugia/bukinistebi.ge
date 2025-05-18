@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container mt-5 col-md-6" style="position: relative; padding-top:10%; padding-bottom:5%;">

        <!-- Validation Errors -->
        @foreach (['name', 'email', 'phone', 'address', 'password', 'password_confirmation'] as $field)
            @if ($errors->has($field))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i> {{ $errors->first($field) }}
                </div>
            @endif
        @endforeach

        <div style="border-radius: 5px; border:1px solid #ccc; padding: 22px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
            <form method="POST" action="{{ route('register.publisher') }}">
                @csrf

                <!-- Name -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" placeholder="სახელი" aria-label="Name" name="name"
                            value="{{ old('name') }}" required autofocus autocomplete="name">
                    </div>
                </div>

                <!-- Phone -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control" placeholder="ტელეფონი" aria-label="Phone" name="phone"
                            value="{{ old('phone') }}" required autocomplete="Phone">
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-house"></i></span>
                        <input type="text" class="form-control" placeholder="მისამართი" aria-label="Address"
                            name="address" value="{{ old('address') }}" required>
                    </div>
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text">@</span>
                        <input type="email" class="form-control" placeholder="ელფოსტა" aria-label="Email" name="email"
                            value="{{ old('email') }}" required autocomplete="username">
                    </div>
                </div>


                <!-- IBAN -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-bank"></i></span>
                        <input type="text" class="form-control" placeholder="საბანკო ანგარიში" aria-label="IBAN" name="iban"
                            value="{{ old('iban') }}">
                    </div>
                </div>



                <!-- Password -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" placeholder="პაროლი" aria-label="Password"
                            name="password" required autocomplete="new-password">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" placeholder="გაიმეორეთ პაროლი"
                            aria-label="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('login.publisher') }}">
                        <span> {{ __('უკვე რეგისტრირებული ხარ?') }} </span>
                    </a>
                    <button type="submit" class="btn btn-success" style="margin-left:5%">
                        {{ __('რეგისტრაცია') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
