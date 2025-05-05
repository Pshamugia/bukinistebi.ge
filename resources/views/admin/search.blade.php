@extends('admin.layouts.app')

@section('title', 'admin search')

@section('content')
 

 <!-- Displaying Search Results -->
 <h4><p> 
    @if ($search_count>0)
    <i class="bi bi-check-square-fill"></i> 
    მოიძებნა 
    <span style="background-color: #dc3545; color:white; padding:4px 0px 0px 3px; border-radius: 3px; margin-right:5px">
        {{ $search_count }} 
    </span> 
        @if ($search_count == 1)
       მასალა
        @else
        მასალა
        @endif
    @else
    <i class="bi bi-dash-circle-fill"></i> {{ "ბაზაში ვერ მოიძებნა"}} 
        <span style="background-color: rgb(177, 20, 20) !important; color:white; padding:6px;">
             {{ $searchTerm }} 
        </span>  &nbsp; დარწმუნდი მართლწერის სისწორეში
    @endif
</p></h4>


@if ($books->count())
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ფოტო</th>
            <th>სახელწოდება</th>
            <th>ავტორი</th>
            <th>კატეგორია</th>
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
                <td>{{ $book->category->name }} /   @if ($book->genres && $book->genres->count())
                    <br>
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
