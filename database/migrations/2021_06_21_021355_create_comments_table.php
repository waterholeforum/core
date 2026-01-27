<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table
                ->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table
                ->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->mediumText('body');
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('edited_at')->nullable()->index();
            $table->unsignedInteger('reply_count')->default(0);
            $table->integer('score')->default(0)->index();

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['body']);
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
