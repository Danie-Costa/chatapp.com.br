<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['room_id','invited_email','token','status','invited_by','expires_at'];

    protected $dates = ['expires_at'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
