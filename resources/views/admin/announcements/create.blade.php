@extends('admin.layouts.app')

@section('content')
<h3>Create Announcement</h3>

<form method="POST" action="{{ route('announcements.store') }}">
@csrf

<label>Title (optional)</label>
<input type="text" name="title" class="form-control mb-2">

<label>Message *</label>
<textarea name="message" class="form-control mb-2" rows="4" required></textarea>

<label>
<input type="checkbox" name="is_active" checked> Active
</label>

<button class="btn btn-success mt-2">Save</button>
</form>
@endsection
