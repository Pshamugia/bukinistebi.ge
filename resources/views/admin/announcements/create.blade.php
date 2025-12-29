@extends('admin.layouts.app')

@section('content')
<h3>Create Announcement</h3>

<form method="POST" action="{{ route('announcements.store') }}">
@csrf


<label>Start Date</label>
<input type="datetime-local" name="starts_at" class="form-control mb-2">

<label>End Date</label>
<input type="datetime-local" name="ends_at" class="form-control mb-2">


<label>Recurrence Type</label>
<select name="recurrence_type" class="form-control mb-2">
    <option value="none">No recurrence</option>
    <option value="daily">Every Day</option>
    <option value="weekly">Every Week</option>
    <option value="monthly">Every Month</option>
</select>

<label>Show At Time (optional)</label>
<input type="time" name="recurrence_time" class="form-control mb-2">


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
