@extends('layouts.app')
 
@section('content')

<div class="container mt-5">
    <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left; justify-content: left;">
        <strong><i class="bi bi-stack-overflow"></i> პროფილის რედაქტირება</strong>
    </h5>

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
            <label for="name" class="form-label">სახელი და გვარი</label>
            <input type="text" name="name" value="{{ old('name', $publisher->name ?? '') }}" class="form-control" required>
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">ელფოსტა</label>
            <input type="email" name="email" value="{{ old('email', $publisher->email ?? '') }}" class="form-control" required>
        </div>

        <!-- Phone Field -->
        <div class="mb-3">
            <label for="phone" class="form-label">ტელეფონი</label>
            <input type="text" name="phone" value="{{ old('phone', $publisher->phone ?? '') }}" class="form-control" required>
        </div>

        <!-- Address Field -->
        <div class="mb-3">
            <label for="address" class="form-label">მისამართი (ჩაწერეთ გარკვევით, რომ კურიერი ზუსტად მოვიდეს)</label>
            <input type="text" name="address" value="{{ old('address', $publisher->address ?? '') }}" class="form-control">
        </div>


            <!-- IBAN Field -->
            <div class="mb-3">
                <label for="iban" class="form-label">საბანკო ანგარიში</label>
                <input type="text" name="iban" value="{{ old('iban', $publisher->iban ?? '') }}" class="form-control">
            </div>

        <!-- Password Fields -->
        <div class="mb-3">
            <label for="password" class="form-label">პაროლის განახლება (თუ გჭირდება)</label>
            <input type="password" name="password" class="form-control" placeholder="ახალი პაროლი">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">ახალი პაროლის დადასტურება</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="დაადასტურე ახალი პაროლი">
        </div>

        <button type="submit" class="btn btn-primary">ცვლილების შენახვა</button>
    </form>
</div>

@endsection
