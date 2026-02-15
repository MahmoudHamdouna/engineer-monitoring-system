@extends('layouts.dashboard')
@section('content')
<h4 class="mb-4">Notifications</h4>

<div class="d-flex justify-content-end mb-2">
    <button class="btn btn-sm btn-outline-primary" onclick="markAllRead()">Mark All Read</button>
</div>

<div class="list-group">
    @foreach($notifications as $note)
    <a href="{{ $note->link ?? '#' }}" 
       class="list-group-item list-group-item-action d-flex justify-content-between
       {{ $note->status=='unread'?'list-group-item-warning fw-bold':'' }}">
        <div>
            <strong>{{ $note->title }}</strong>
            <p class="mb-0">{{ $note->message }}</p>
        </div>
        <small>{{ $note->created_at->diffForHumans() }}</small>
    </a>
    @endforeach
</div>

<script>
function markAllRead(){
    fetch("{{ route('notifications.markAllRead') }}",{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(()=>location.reload());
}
</script>
@endsection
