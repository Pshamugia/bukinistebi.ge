 
 
<div class="container">
   <div  style="padding:20px 0px 20px ;">  <h5 class="btn btn-secondary w-100"> 📊 ელფოსტის სტატისტიკა </h5> </div>


    <form method="GET" action="{{ route('admin.subscribers') }}" class="mb-3">
        <div class="input-group"  >
            <select name="range" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ $range == 'all' ? 'selected' : '' }}>ყველა დრო</option>
                <option value="24h" {{ $range == '24h' ? 'selected' : '' }}>ბოლო 24 საათი</option>
                <option value="7d" {{ $range == '7d' ? 'selected' : '' }}>ბოლო 7 დღე</option>
                <option value="30d" {{ $range == '30d' ? 'selected' : '' }}>ბოლო 30 დღე</option>
            </select>
            <button type="submit" class="btn btn-warning">სტატისტიკა</button>
        </div>
    </form>
    <ul class="list-group">
        <li class="list-group-item">Queued Emails: {{ $queued }}</li>
        <li class="list-group-item">Failed Emails: {{ $failed }}</li>
        <li class="list-group-item">Opened Emails: {{ $opened }}</li>
    </ul>
    
    <form method="POST" action="{{ route('admin.email.retry') }}" class="mt-3">
    @csrf
    <button class="btn btn-warning">🔁 Retry Failed Emails</button>
</form>
</div>


 