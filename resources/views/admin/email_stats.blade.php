 
 
<div class="container mt-5">
    <h3> 📊 ელფოსტის სტატისტიკა </h3>
    <ul class="list-group">
        <li class="list-group-item">Queued Emails: {{ $queued }}</li>
        <li class="list-group-item">Failed Emails: {{ $failed }}</li>
        <li class="list-group-item">Opened Emails: {{ $opened }}</li>
    </ul>
</div>
<form method="POST" action="{{ route('admin.email.retry') }}" class="mt-3">
    @csrf
    <button class="btn btn-warning">🔁 Retry Failed Emails</button>
</form>

 