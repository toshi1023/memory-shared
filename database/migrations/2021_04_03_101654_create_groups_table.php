<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('name')->comment('グループ名');
            $table->text('description')->nullable()->comment('紹介文');
            $table->boolean('private_flg')->default(0)->comment('公開フラグ');
            $table->boolean('welcome_flg')->default(0)->comment('歓迎フラグ');
            $table->string('image_file')->nullable()->comment('画像名');
            $table->integer('host_user_id')->unsigned()->nullable()->comment('ホストID');
            $table->text('memo')->nullable()->comment('備考');
            $table->integer('update_user_id')->unsigned()->nullable()->comment('更新ユーザ');

            $table->timestamps();

            // 外部キー制約
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
        Schema::dropIfExists('groups');
    }
}
