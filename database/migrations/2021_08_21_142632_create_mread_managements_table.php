<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMreadManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mread_managements', function (Blueprint $table) {
            $table->bigInteger('message_id')->unsigned()->comment('トーク履歴ID');
            $table->integer('user_id')->unsigned()->comment('未読ユーザID');

            $table->timestamps();

            // プライマリキー設定
            $table->unique(['message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mread_managements');
    }
}
