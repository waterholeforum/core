<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->mediumText('body')->fulltext();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('edited_at')->nullable()->index();
            $table->unsignedInteger('reply_count')->default(0);
            $table->unsignedInteger('score')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
