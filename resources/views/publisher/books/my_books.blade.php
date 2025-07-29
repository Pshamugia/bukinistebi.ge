@extends('layouts.app')

@section('title', 'ჩემი ატვირთული წიგნები')

@section('content')
<div class="container mt-5" style="min-height: 400px; position: relative; top:30px;">
    <h5 class="section-title" style="position: relative; margin-bottom:15px; padding-bottom:15px; align-items: left;
    justify-content: left;">     <strong>{{ __('messages.myUploadedBooks')}}</h5>
    @if($books->isEmpty())
        <p>{{ __('messages.notUploaded')}}</p>
    @else
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="background-color: #d1d1d1">
                    <th>{{ __('messages.image')}}</th>
                    <th>{{ __('messages.bookTitle')}}</th>
                    <th>{{ __('messages.price')}}</th>
                    <th>{{ __('messages.category')}}</th>
                    <th>{{ __('messages.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>
                            @if($book->photo)
                                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" width="50">
                            @endif
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ number_format($book->price) }} ₾</td>
                        <td>{{ $book->category->name ?? 'კატეგორიის გარეშე' }}</td>
                        <td>
                            <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="btn btn-info btn-sm">
                                {{ __('messages.view')}}
                            </a>
 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
