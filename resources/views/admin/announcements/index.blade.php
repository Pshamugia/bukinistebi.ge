@extends('admin.layouts.app')

@section('content')
<h3>Global Announcements</h3>

<a href="{{ route('announcements.create') }}" class="btn btn-primary mb-3">Create Announcement</a>

<table class="table table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Message</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@foreach($announcements as $a)
<tr>
    <td>{{ $a->id }}</td>
    <td>{{ $a->message }}</td>
    <td>
        @if($a->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-secondary">Hidden</span>
        @endif
    </td>

    <td>
        <a href="{{ route('announcements.toggle',$a->id) }}" class="btn btn-warning btn-sm">
            {{ $a->is_active ? 'Hide' : 'Activate' }}
        </a>

        <form action="{{ route('announcements.delete',$a->id) }}" method="POST" style="display:inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete announcement?')">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
@endsection
