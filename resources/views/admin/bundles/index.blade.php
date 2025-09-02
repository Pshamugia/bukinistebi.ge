{{-- resources/views/admin/bundles/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Bundle Offers</h4>
    <a href="{{ route('admin.bundles.create') }}" class="btn btn-primary">+ New Bundle</a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Title</th>
        <th>Books</th>
        <th>Original</th>
        <th>Bundle Price</th>
        <th>Active</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach($bundles as $b)
      <tr>
        <td>{{ $b->title }}</td>
        <td>
          @foreach($b->books as $bk)
            <div>{{ $bk->title }} × {{ $bk->pivot->qty }}</div>
          @endforeach
        </td>
        <td>{{ number_format($b->original_price) }} GEL</td>
        <td><strong>{{ number_format($b->price) }} GEL</strong> (−{{ max(0, $b->original_price - $b->price) }} GEL)</td>
        <td>{!! $b->active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
        <td class="text-end">
          <a href="{{ route('admin.bundles.edit',$b) }}" class="btn btn-sm btn-outline-primary">Edit</a>
          <form action="{{ route('admin.bundles.destroy',$b) }}" method="post" class="d-inline"
                onsubmit="return confirm('Delete bundle?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $bundles->links() }}
</div>
@endsection
