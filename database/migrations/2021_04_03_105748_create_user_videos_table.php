<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_videos', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('image_file')->comment('動画名');                        // サーバーサイドの動画名
            $table->integer('user_id')->unsigned()->comment('ユーザID');
            $table->integer('album_id')->unsigned()->comment('アルバムID');
            $table->string('type')->default('video/mp4')->comment('動画のファイルタイプ');
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
        Schema::dropIfExists('user_videos');
    }
}
