@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">

    <h3>Sub-admins</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- CREATE SUB-ADMIN --}}
    <form method="POST" action="{{ route('admin.subadmins.create') }}" class="card mb-4">
        @csrf
        <div class="card-header">Create sub-admin</div>
        <div class="card-body row">
            <div class="col-md-4">
                <input name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="col-md-4">
                <input name="email" type="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-4">
                <input name="password" type="password" class="form-control" placeholder="Password" required>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success btn-sm">Create</button>
        </div>
    </form>

    {{-- PERMISSIONS --}}
    @foreach($users as $user)

    <form method="POST"
        action="{{ route('admin.subadmins.update', $user->id) }}"
        class="card mb-3">
        @csrf

        <div class="card-header">
            {{ $user->name }} ({{ $user->email }})
        </div>

        <div class="card-body row">

@php
    $current = is_array($user->admin_permissions)
        ? $user->admin_permissions
        : json_decode($user->admin_permissions ?? '[]', true);
@endphp

            @foreach([
            'dashboard.view' => 'Dashboard',
            'books.view'   => 'View books',
            'books.manage' => 'Books',
            'books.delete' => 'Delete books',
            'book_news.manage' => 'Book news',
            'orders.manage' => 'Orders',
            'transactions.manage' => 'Transactions',
            'auctions.manage' => 'Auctions',
            'users.manage' => 'Users',
            'qookies.manage' => 'Qookies',
            'bundles.manage' => 'Bundles',
            'exports.use' => 'Exports',
            'analytics.view' => 'Analytics',
            ] as $key => $label)

            <div class="col-md-3">
                <label>
                    <input type="checkbox"
                        name="permissions[]"
                        value="{{ $key }}"
                        {{ in_array($key, $current) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            </div>

            @endforeach
        </div>

        <div class="card-footer text-end">
            <button class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>

    @endforeach

</div>
@endsection