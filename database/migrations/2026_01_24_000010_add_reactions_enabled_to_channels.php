<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->boolean('posts_reactions_enabled')->default(true)->after('ignore');
            $table
                ->boolean('comments_reactions_enabled')
                ->default(true)
                ->after('posts_reaction_set_id');
        });
    }

    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('posts_reactions_enabled');
            $table->dropColumn('comments_reactions_enabled');
        });
    }
};
