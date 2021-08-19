<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->default(0)->comment('ユーザID');            // 全体向けのニュースは"0"を設定
            $table->integer('news_id')->comment('ニュースID');
            $table->string('title')->comment('タイトル');
            $table->text('content')->comment('内容');
            $table->integer('update_user_id')->unsigned()->comment('更新ユーザ');

            $table->timestamps();

            // 外部キー制約
            $table->foreign('update_user_id')->references('id')->on('users')->onDelete('cascade');

            // プライマリキー設定
            $table->unique(['user_id', 'news_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
