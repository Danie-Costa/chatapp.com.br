@extends('layouts.app')
@section('content')
<h3>Salas</h3>
<div class="list-group">
  
  @foreach($rooms ?? [] as $room)
    <a href="/rooms/{{ $room['id'] }}" class="list-group-item list-group-item-action">
      {{ $room['name'] }}
    </a>
  @endforeach
</div>
@endsection
