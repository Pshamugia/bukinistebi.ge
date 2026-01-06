@extends('layouts.app')
 
@section('content')

<div class="container mt-5" style="position: relative; top:30px;">
    <h5 class="section-title" style="position: relative; margin-bottom:15px; padding-bottom:15px; align-items: left; justify-content: left;">
        <strong><i class="bi bi-stack-overflow"></i> {{ __('messages.editProfile')}}</strong>
    </h5>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
         <p class="mb-0">
            {{ session('success') }}
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                @if(is_string($error))
                    <li>{{ $error }}</li>
                @endif
            @endforeach
        </ul>
    </div>
@endif


    <!-- Success message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('publisher.account.update') }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PUT">

        @method('PUT') <!-- Use PUT to indicate an update operation -->

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('messages.nameSurname')}}</label>
            <input type="text" name="name" value="{{ old('name', $publisher->name ?? '') }}" class="form-control" required>
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('messages.email')}}</label>
            <input type="email" name="email" value="{{ old('email', $publisher->email ?? '') }}" class="form-control" required>
        </div>

        <!-- Phone Field -->
        <div class="mb-3">
            <label for="phone" class="form-label">{{ __('messages.phoneNumber')}}</label>
            <input type="text" name="phone" value="{{ old('phone', $publisher->phone ?? '') }}" class="form-control" required>
        </div>

        <!-- Address Field -->
        <div class="mb-3">
            <label for="address" class="form-label">{{ __('messages.address')}} ({{ __('messages.preciseAddress')}})</label>
            <input type="text" name="address" value="{{ old('address', $publisher->address ?? '') }}" class="form-control">
        </div>


            <!-- IBAN Field -->
            <div class="mb-3">
                <label for="iban" class="form-label">{{ __('messages.bankAccount')}}</label>
                <input type="text" name="iban" value="{{ old('iban', $publisher->iban ?? '') }}" class="form-control">
            </div>

        <!-- Password Fields -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('messages.updatePassword')}}</label>
            <input type="password" name="password" class="form-control" placeholder="{{ __('messages.newPassword')}}">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('messages.confirm')}}</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.confirm')}}">
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.saveChanges')}}</button>
    </form>
</div>

@endsection
