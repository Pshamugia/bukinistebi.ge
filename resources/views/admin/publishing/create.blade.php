@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Publishing ჩანაწერის დამატება</h2>

    <form method="POST" action="{{ route('admin.publishing.store') }}" enctype="multipart/form-data" class="card shadow-sm p-4">
        @csrf
        @include('admin.publishing.partials.form', ['item' => $item, 'buttonText' => 'შენახვა'])
    </form>
</div>
@endsection
