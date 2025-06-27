@extends('layouts.app')

@section('title', 'რედაქტირება')
 
@section('content')
<div class="container" style="position: relative; top:30px; ">
    <h5 class="section-title" style="position: relative; margin-bottom:25px;   padding-bottom:25px; align-items: left;
    justify-content: left;">        <strong>
            <i class="bi bi-person-fill-gear"></i> {{ __('messages.editProfile')}}
        </strong>
    </h5>

    <!-- Display Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> {{ __('messages.error')}}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('account.update') }}" method="POST">
        @csrf

        <!-- Name Field -->
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="name" class="form-control" placeholder="{{ __('messages.name')}}" id="name" 
                   value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- Email Field -->
        <div class="input-group mb-3">
            <span class="input-group-text">@</span>
            <input type="email" name="email" class="form-control" placeholder="{{ __('messages.email')}}" id="email" 
                   value="{{ old('email', $user->email) }}" required>
        </div>

        <!-- Password Fields -->
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="{{ __('messages.newPassword') }}" id="password">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.confirm') }}" id="password_confirmation">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">{{ __('messages.updateProfile') }}</button>
    </form>
</div>
@endsection
