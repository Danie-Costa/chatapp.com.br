<?php
namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function view(User $user, Room $room)
    {
        if (! $room->is_private) {
            return true;
        }

        // owner or member
        if ($room->owner_id === $user->id) return true;

        return $room->users()->where('users.id', $user->id)->exists();
    }

    public function create(User $user)
    {
        // qualquer usuário autenticado pode criar sala
        return true;
    }

    public function update(User $user, Room $room)
    {
        // owner ou admins (pivot role=admin)
        if ($room->owner_id === $user->id) return true;

        $pivot = $room->users()->where('users.id', $user->id)->first();

        return $pivot && $pivot->pivot->role === 'admin';
    }

    public function delete(User $user, Room $room)
    {
        return $room->owner_id === $user->id;
    }

    public function invite(User $user, Room $room)
    {
        // somente owner ou admin pode convidar
        return $this->update($user, $room) || $room->owner_id === $user->id;
    }

    public function join(User $user, Room $room)
    {
        // se for privado, precisa de convite (checada na controller), se público libera
        return ! $room->is_private || $room->users()->where('users.id', $user->id)->exists();
    }
}
