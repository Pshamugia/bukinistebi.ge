@extends('layouts.app')

@section('title', 'გადახდა შესრულებულია')

@section('content')
<div class="container py-5 text-center">
    <h2 class="text-success mb-4">✅ გადახდა შესრულებულია წარმატებით!</h2>
    <p class="mb-4">თქვენი შეკვეთა მიღებულია და მალე დაგიკავშირდებით.</p>

    <a href="{{ route('welcome') }}" class="btn btn-primary">მთავარ გვერდზე დაბრუნება</a>
</div>
@endsection