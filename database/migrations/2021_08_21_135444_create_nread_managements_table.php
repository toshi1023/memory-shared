<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNreadManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nread_managements', function (Blueprint $table) {
            $table->bigInteger('news_user_id')->unsigned()->comment('ニュース受信者ID');
            $table->bigInteger('news_id')->unsigned()->comment('ニュースID');
            $table->integer('user_id')->unsigned()->comment('未読ユーザID');

            $table->timestamps();

            // プライマリキー設定
            $table->unique(['news_user_id', 'news_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('read_managements');
    }
}
