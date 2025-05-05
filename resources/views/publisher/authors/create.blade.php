@extends('layouts.app')
 
@section('content')
<div class="container">
    <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left">
        დაამატე ავტორი</h5>

        @if ($errors->any())
    <div class="alert alert-danger">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    
    </div>
@endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('publisher.authors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="author_name" class="form-label">ავტორის სახელი და გვარი</label>
            <input type="text" name="name" class="form-control" id="author_name" required>
        </div>


        
        <button type="submit" class="btn btn-primary">ავტორის დამატება</button>
    </form>
</div>

@endsection
