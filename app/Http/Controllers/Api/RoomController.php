<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // listar salas do usuário + públicas (paginado)
        $rooms = Room::where(function($q) use ($user) {
            $q->where('is_private', false)
              ->orWhereHas('users', function($q2) use ($user) {
                  $q2->where('users.id', $user->id);
              });
        })->with('owner')->paginate(20);

        return response()->json($rooms);
    }

    public function store(\App\Http\Requests\StoreRoomRequest $request)
    {
        $user = $request->user();

        $room = Room::create([
            'name' => $request->name,
            'owner_id' => $user->id,
            'is_private' => $request->boolean('is_private', true),
            'description' => $request->description,
        ]);
       
        // adicionar owner na pivot como owner
        $room->users()->attach($user->id, ['role' => 'owner', 'joined_at' => now()]);

        return response()->json($room, 201);
    }

    public function show(Request $request, Room $room)
    {
        $this->authorize('view', $room);

        $room->load(['users:id,name,email','messages' => function($q) {
            $q->latest()->limit(100)->with('user');
        }]);

        return response()->json($room);
    }

    public function invite(\App\Http\Requests\InviteRequest $request, Room $room)
    {
        $this->authorize('invite', $room);

        $token = Str::random(40);
        $inv = Invitation::create([
            'room_id' => $room->id,
            'invited_email' => $request->invited_email,
            'token' => $token,
            'status' => 'pending',
            'invited_by' => $request->user()->id,
            'expires_at' => $request->expires_at,
        ]);

        // enviar email / queue job — aqui apenas retornamos token
        return response()->json(['invitation' => $inv], 201);
    }
}
