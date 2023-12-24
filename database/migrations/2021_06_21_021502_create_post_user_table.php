<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('post_user', function (Blueprint $table) {
            $table
                ->foreignId('post_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamp('last_read_at')->nullable();
            $table->string('notifications')->nullable();
            $table->timestamp('followed_at')->nullable();
            $table->timestamp('mentioned_at')->nullable();

            $table->primary(['post_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_user');
    }
};
