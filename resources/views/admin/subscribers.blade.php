@extends('admin.layouts.app')

@section('title', 'გამომწერები')

@section('content')
<div class="container" style="position: relative; margin-top:55px;">
    <h1>გამომწერები</h1>

        
    @include('admin.email_stats', [
        'queued' => $queued ?? 0,
        'failed' => $failed ?? 0,
        'opened' => $opened ?? 0
    ])
<br><br>


   
   {{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

{{-- Error Message --}}
@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<a href="{{ route('admin.subscribeAllUsers') }}" class="btn btn-primary">
    ყველა მომხმარებლის გადმოყვანა გამომწერებად
</a>

<form method="POST" action="{{ route('send.subscriber.email') }}" id="subscriber-form">        @csrf


        <div class="mb-3">
            <label for="custom_subject" class="form-label">სათაური ელფოსტისთვის</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-left-text"></i></span>
                <input type="text" name="custom_subject" id="custom_subject" class="form-control"
                       placeholder="მაგალითად: ნახე ახალი დამატებული წიგნი">
            </div>
        </div>

        <div class="mb-3">
            <label for="custom_message" class="form-label">შეტყობინება</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-pencil-square"></i></span>
                <textarea name="custom_message" id="custom_message" class="form-control" rows="3"
                          placeholder="მაგალითად: ჩვენს ბუკინისტებზე დაემატა საინტერესო მასალა..."></textarea>
            </div>
        </div>
        <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

    <script>
        // Replace the <textarea> with a CKEditor instance
        CKEDITOR.replace('custom_message');
    </script>
        
        
        <div class="mb-3">
            <button type="submit" class="btn btn-success">გამოაგზავნე ელფოსტა მონიშნულებზე</button>
        </div>
    </form>
    <table class="table table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>ელფოსტა</th>
                <th>ქმედება</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $subscriber)
                <tr>
                    <td>
                        <!-- ✅ Link checkbox to email form -->
                        <input type="checkbox" name="emails[]" value="{{ $subscriber->email }}" form="subscriber-form">
                    </td>
                    <td>{{ $loop->iteration }}. {{ $subscriber->email }}</td>
                    <td>
                        <!-- ✅ Separate form for DELETE -->
                        <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" onsubmit="return confirm('ნამდვილად გსურთ წაშლა?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">X</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
 
    
    <script>
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="emails[]"]');
            const isChecked = this.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });
    </script>
    
    
    
    
</div>
@endsection