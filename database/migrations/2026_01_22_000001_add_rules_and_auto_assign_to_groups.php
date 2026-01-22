<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->boolean('auto_assign')->default(0);
            $table->json('rules')->nullable();
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['auto_assign', 'rules']);
        });
    }
};
