<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('invited_email')->nullable();
            $table->string('token')->unique();
            $table->string('status')->default('pending'); // pending, accepted, declined, expired
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['room_id', 'invited_email', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
