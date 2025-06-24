@extends('layouts.app')

@section('title', 'გადახდა შესრულებულია')

@section('content')
    <div class="container py-5 text-center">
        <h2 class="text-success mb-4">თქვენი შეკვეთის სტატუსია: {{ $status->label }} </h2>
        @if ($status->key == 'success')
            <p class="mb-4">თქვენი შეკვეთა მიღებულია და მალე დაგიკავშირდებით.</p>
        @endif
        <a href="{{ route('welcome') }}" class="btn btn-primary">მთავარ გვერდზე დაბრუნება</a>
    </div>
@endsection
