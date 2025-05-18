@extends('admin.layouts.app')

@section('title', 'ბუკინისტები')

@section('content')
<div class="container" style="position: relative; margin-top:55px;">
    <h1>ბუკინისტები</h1>
    <table class="table table-bordered  table-hover">
        <thead>
            <tr>
                <th>სახელი</th>
                <th>საკოტაქტო</th> 
                <th>მისამართი</th>
                <th>საბანკო ანგარიში</th>
                <th>ატვირთული</th>
            </tr>
        </thead>
        <tbody>
            @foreach($publishers as $publisher)
                <tr>
                    <td>{{ $publisher->name }}</td>
                    <td> <i class="bi bi-envelope-fill"></i> {{ $publisher->email }} 
                        <br> <i class="bi bi-telephone-fill"></i> {{ $publisher->phone ?? 'N/A' }}</td>
                 
                    <td> {{ $publisher->address ?? 'N/A' }} </td>
                    <td> {{ $publisher->iban ?? 'N/A' }} </td>
                    <td>
                        @if($publisher->books->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($publisher->books as $book)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="text-decoration-none">
                                        {{ $loop->iteration }}. {{ $book->title }}
                                    </a>
                                 </li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-muted">არ აქვს ატვირთული წიგნები</span>
                    @endif
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    
    
</div>
@endsection