@extends('layouts.app')
@section('content')
<h3>{{ $room['name'] }}</h3>

<div class="card">
  <div class="card-body" id="messages-box" style="height: 400px; overflow-y: auto;">
    @foreach($messages ?? [] as $msg)
      <p><strong>{{ $msg['user']['name'] ?? 'Desconhecido' }}:</strong> {{ $msg['content'] }}</p>
    @endforeach
  </div>
</div>

<form id="messageForm" class="mt-3">
  <div class="input-group">
    <input type="text" id="messageInput" class="form-control" placeholder="Digite uma mensagem...">
    <div class="input-group-append">
      <button class="btn btn-primary">Enviar</button>
    </div>
  </div>
</form>
@endsection

@section('scripts')

<script>
  const roomId = {{ $room['id'] }};
  const token = "{{ session('api_token') }}";
  const user = @json(session('user'));

  $('#messageForm').on('submit', function(e){
    e.preventDefault();
    const content = $('#messageInput').val();

    $.ajax({
      url: `http://127.0.0.1:8081/api/v1/rooms/${roomId}/messages`,
      method: 'POST',
      headers: { 'Authorization': 'Bearer ' + token },
      contentType: 'application/json',
      data: JSON.stringify({ content }),
      success: function(data){
        $('#messages-box').append(`<p><strong>${user.name}:</strong> ${data.content}</p>`);
        $('#messageInput').val('');
      }
    });
  });

  
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('80f8bddaef41c4329f32', {
      cluster: 'sa1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('new-message', function(data) {
      window.location.reload(); 
    });
</script> 
@endsection
