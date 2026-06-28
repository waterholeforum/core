<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->boolean('is_highlighted')->default(0);
            $table->index(['post_id', 'is_highlighted', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'is_highlighted', 'created_at']);
            $table->dropColumn('is_highlighted');
        });
    }
};
