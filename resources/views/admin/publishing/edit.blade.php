@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Publishing ჩანაწერის რედაქტირება</h2>

    <form method="POST" action="{{ route('admin.publishing.update', $item) }}" enctype="multipart/form-data" class="card shadow-sm p-4">
        @csrf
        @method('PUT')
        @include('admin.publishing.partials.form', ['item' => $item, 'buttonText' => 'განახლება'])
    </form>
</div>
@endsection
