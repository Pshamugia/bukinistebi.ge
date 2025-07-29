<!-- resources/views/admin/users_transactions.blade.php -->

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
 
<div class="container">
    <h2>{{ __('მომხმარებლების სია') }}</h2>
     
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('მომხმარებლის სახელი') }}</th>
                <th>{{ __('ელფოსტა') }}</th> <!-- New column for the new purchase amount -->
                 <th>{{ __('ტელეფონი') }}</th>
             </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td><a href="{{ route('admin.user.details', $user->id) }}">{{ $user->name }}</a></td>
                    <td>
                        {{ $user->email }}    
                    </td>
                   
                    <td>{{ $user->mobile ? '' : 'არაა მითითებული'  }}</td>
                 </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    {{ $users->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->

</div> 
@endsection
