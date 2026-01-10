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
                <th>{{ __('ქმედება') }}</th>

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
                <td>
                    <form action="{{ route('admin.users.delete', $user->id) }}"
                        method="POST"
                        onsubmit="return confirm('ნამდვილად გსურთ მომხმარებლის წაშლა?');"
                        style="display:inline-block">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    {{ $users->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->

</div>
@endsection