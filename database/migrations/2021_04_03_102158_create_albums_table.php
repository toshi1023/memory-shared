<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('name')->comment('アルバム名');
            $table->integer('group_id')->unsigned()->comment('グループID');
            $table->string('image_file')->nullable()->comment('画像名');
            $table->integer('host_user_id')->unsigned()->nullable()->comment('ホストID(アルバム作成者)');
            $table->text('memo')->nullable()->comment('備考');
            $table->integer('update_user_id')->unsigned()->nullable()->comment('更新ユーザ');

            $table->timestamps();

            // 外部キー制約
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('host_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('update_user_id')->references('id')->on('users')->onDelete('set null');

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
        Schema::dropIfExists('albums');
    }
}
