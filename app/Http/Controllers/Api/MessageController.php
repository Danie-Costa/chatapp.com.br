<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Events\MessageSent; // para broadcasting
use App\Http\Requests\StoreMessageRequest;

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

        return response()->json($message, 201);
    }
}
