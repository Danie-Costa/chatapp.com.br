<?php
namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function create(User $user, $room)
    {
        // $room pode ser Room ou id; checar se user é membro
        return $room->users()->where('users.id', $user->id)->exists() || $room->owner_id === $user->id;
    }

    public function delete(User $user, Message $message)
    {
        // autor da mensagem ou dono da sala
        if ($message->user_id === $user->id) return true;
        return $message->room && $message->room->owner_id === $user->id;
    }

    public function update(User $user, Message $message)
    {
        return $message->user_id === $user->id;
    }
}
