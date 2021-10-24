<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('channel_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('channel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('notifications')->nullable();
            $table->timestamp('followed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_user');
    }
};
