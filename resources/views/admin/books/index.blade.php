@extends('admin.layouts.app')

@section('title', 'Books')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>წიგნები</h1>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">დაამატე წიგნი</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.books.index') }}" class="mb-3">
        <label for="quantity">{{ __('რაოდენობით გაფილტვრა:') }}</label>
        <select name="quantity" id="quantity" class="form-control w-25">
            <option value="">{{ __('მაჩვენე ყველა') }}</option>
            <option value="0" {{ request('quantity') == '0' ? 'selected' : '' }}>{{ __('ამოწურულია (0)') }}</option>
            <option value="1" {{ request('quantity') == '1' ? 'selected' : '' }}>{{ __('მარაგშია (1)') }}</option>
            <option value="2" {{ request('quantity') == '2' ? 'selected' : '' }}>{{ __('მარაგშია (2)') }}</option>
            <option value="3" {{ request('quantity') == '3' ? 'selected' : '' }}>{{ __('მარაგშია (3)') }}</option>
            <!-- Add more options as needed -->
        </select>
        <button type="submit" class="btn btn-primary mt-2">{{ __('გაფილტრე') }}</button>
    </form>

    @if ($books->count())
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ფოტო</th>
                    <th>სახელწოდება</th>
                    <th>ავტორი</th>
                    <th>კატეგორია / ჟანრი</th>
                    <th width="200px">ქმედება</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>
                            @if (isset($book->photo))
                            <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="img-fluid" width="150">
                        @endif
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author->name }}</td>
                        <td> @if ($book->genres && $book->genres->count())
                           
                            <small>ჟანრი: 
                                {{ $book->genres->pluck('name')->implode(', ') }}
                            </small>
                        @endif</td>
                        <td>
                            <form action="{{ route('admin.books.toggleVisibility', $book->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn {{ $book->hide ? 'btn-danger btn-sm' : 'btn-warning btn-sm' }}">
                                    @if($book->hide)
                                        Show
                                    @else
                                        Hide
                                    @endif
                                </button>
                            </form>
                            
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this book?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div> {{ $books->links('pagination.custom-pagination') }} </div>
    @else
        <div class="alert alert-warning">No books available.</div>
    @endif
@endsection
