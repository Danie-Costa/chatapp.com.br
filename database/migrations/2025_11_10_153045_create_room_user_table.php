<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomUserTable extends Migration
{
    public function up()
    {
        Schema::create('room_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->default('member'); // owner, admin, member
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['room_id','user_id']);
            $table->index(['user_id','room_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_user');
    }
}
