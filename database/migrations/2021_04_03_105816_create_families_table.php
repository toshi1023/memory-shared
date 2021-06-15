<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('own_id')->unsigned()->comment('ユーザID(自身)');
            $table->integer('user_id')->unsigned()->comment('ユーザID(相手)');
            $table->integer('update_user_id')->unsigned()->comment('更新ユーザ');

            $table->timestamps();

            // 外部キー制約
            $table->foreign('own_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('families');
    }
}
