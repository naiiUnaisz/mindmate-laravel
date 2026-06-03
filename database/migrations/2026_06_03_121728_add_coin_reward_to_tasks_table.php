<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('coin_reward')->default(10)->after('description');
            $table->string('task_type')->default('keranjang')->after('coin_reward');
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['coin_reward', 'task_type']);
        });
    }
    };
