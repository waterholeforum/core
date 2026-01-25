<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->foreignId('group_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->primary(['group_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_user');
    }
};
