<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->boolean('is_approved')->default(1)->index()->after('score');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
