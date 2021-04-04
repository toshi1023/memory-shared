<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_images', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('name')->comment('画像名');                      // サーバーサイドの画像名
            $table->string('title')->comment('画像タイトル');               // フロントエンドの画像名
            $table->integer('user_id')->unsigned()->comment('ユーザID');
            $table->integer('album_id')->unsigned()->comment('アルバムID');
            $table->json('black_list')->nullable()->comment('ブラックリスト');
            $table->json('white_list')->nullable()->comment('ホワイトリスト');
            $table->integer('update_user_id')->unsigned()->nullable()->comment('更新ユーザ');

            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_images');
    }
}
