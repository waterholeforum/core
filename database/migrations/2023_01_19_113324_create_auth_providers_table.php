<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('auth_providers', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('provider');
            $table->string('identifier');
            $table->timestamp('created_at');
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auth_providers');
    }
};
