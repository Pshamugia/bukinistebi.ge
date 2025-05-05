@extends('layouts.app')

@section('title', 'ჩემი ატვირთული წიგნები')

@section('content')
<div class="container mt-5" style="min-height: 400px">
    <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>ჩემი ატვირთული წიგნები</h5>
    @if($books->isEmpty())
        <p>თქვენ ჯერ არ აგიტვირთავთ წიგნები. </p>
    @else
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="background-color: #d1d1d1">
                    <th>სურათი</th>
                    <th>სათაური</th>
                    <th>ფასი</th>
                    <th>კატეგორია</th>
                    <th>ქმედებები</th>
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
                                ნახვა
                            </a>
 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
