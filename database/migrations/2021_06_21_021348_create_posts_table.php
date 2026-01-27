<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table
                ->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->mediumText('body');
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('edited_at')->nullable()->index();
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->unsignedInteger('comment_count')->default(0)->index();
            $table->integer('score')->default(0)->index();
            $table->boolean('is_locked')->default(0);

            $table->index(['channel_id', 'created_at']); // used to get new post count within each channel

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['title']);
                $table->fullText(['body']);
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
