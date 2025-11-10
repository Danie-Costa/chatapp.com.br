<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Events\MessageSent; // para broadcasting
use App\Http\Requests\StoreMessageRequest;
use App\Events\MyEvent;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function index(Request $request, Room $room)
    {
        $this->authorize('view', $room);

        $messages = $room->messages()->with('user')->latest()->paginate(50);

        return response()->json($messages);
    }

    public function store(StoreMessageRequest $request, Room $room)
    {
        $this->authorize('create', $room); // verificação se é membro
        $user = $request->user();

        $message = Message::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        // broadcast via event (MessageSent implements ShouldBroadcast)
        MessageSent::dispatch($message);

        event(new MyEvent('hello world'));

        // Send POST to external API
        Http::withHeaders([
            'Authorization' => 'Bearer eb585429a1ce1f7b4733272abb1e8d96b67f128a9d63e1adc291775cfbb65caf',
        ])->post('https://www.notfy.nineweb.com.br/api/notify/pusher', [
            'channel' => 'my-channel',
            'event' => 'new-message',
            'data' => [
            'redirect' => 'true',
            ],
        ]);

        return response()->json($message, 201);
        }
    }
