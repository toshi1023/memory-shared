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
            // $table->bigInteger('message_id')->unique()->unsigned()->comment('トーク履歴ID');
            $table->bigInteger('message_id')->unsigned()->comment('トーク履歴ID');
            $table->integer('own_id')->unsigned()->comment('送信者ID');
            $table->integer('user_id')->unsigned()->comment('未読ユーザID');

            $table->timestamps();
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
