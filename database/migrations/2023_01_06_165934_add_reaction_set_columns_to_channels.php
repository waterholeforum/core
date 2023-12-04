<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table
                ->foreignId('posts_reaction_set_id')
                ->nullable()
                ->constrained('reaction_sets')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table
                ->foreignId('comments_reaction_set_id')
                ->nullable()
                ->constrained('reaction_sets')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropConstrainedForeignId('posts_reaction_set_id');
            $table->dropConstrainedForeignId('comments_reaction_set_id');
        });
    }
};
