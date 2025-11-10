<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Room;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function accept(Request $request, $token)
    {
        $inv = Invitation::where('token',$token)->firstOrFail();

        if ($inv->expires_at && $inv->expires_at->isPast()) {
            $inv->update(['status' => 'expired']);
            return response()->json(['message'=>'Invitation expired'], 400);
        }

        // se o usuário autenticado tem mesmo email?
        $user = $request->user();

        // opcional: checar invited_email == $user->email
        if ($inv->invited_email && $inv->invited_email !== $user->email) {
            return response()->json(['message'=>'Invitation not for this user'], 403);
        }

        // adicionar na pivot e marcar accepted
        $room = Room::findOrFail($inv->room_id);

        $room->users()->syncWithoutDetaching([$user->id => ['role'=>'member','joined_at'=>now()]]);

        $inv->update(['status'=>'accepted']);

        return response()->json(['message'=>'Joined room','room'=>$room]);
    }
}
