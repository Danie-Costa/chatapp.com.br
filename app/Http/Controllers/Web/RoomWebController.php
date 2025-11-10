<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Room;
class RoomWebController extends Controller
{
    public function index()
    {
        $user = session('user');
        $rooms = Room::where(function($q) use ($user) {
            $q->where('is_private', false)
              ->orWhereHas('users', function($q2) use ($user) {
                  $q2->where('users.id',  $user['id']);
              });
        })->with('owner')->paginate(20);

        return view('rooms.index', compact('rooms'));
    }

    public function show($id)
    {
        $room = \App\Models\Room::findOrFail($id);
        $messages = $room->messages;
        return view('rooms.show', compact('room', 'messages'));
    }
}
