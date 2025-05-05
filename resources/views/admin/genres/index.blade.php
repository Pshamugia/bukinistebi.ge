@extends('admin.layouts.app')

@section('title', 'Genres')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>ჟანრები</h1>
        <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">დაამატე ჟანრი</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($genres->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>დასახელება</th>
                    <th width="150">ქმედება</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($genres as $genre)
                    <tr>
                        <td>{{ $genre->name }}</td>
                        <td>
                            <a href="{{ route('admin.genres.edit', $genre) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this genre?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No genres available.</p>
    @endif
@endsection
