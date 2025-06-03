@extends('admin.layouts.app')

@section('title', 'გამომწერები')

@section('content')
<div class="container" style="position: relative; margin-top:55px;">
    <h1>გამომწერები</h1>
    <table class="table">
        <thead>
            <tr>
                          <th>ელფოსტა</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $subscriber)
                <tr>
                     <td>{{ $loop->iteration }}. {{ $subscriber->email }}</td>
                     
                    
                </tr>
            @endforeach
        </tbody>
    </table>
    
    
    
</div>
@endsection