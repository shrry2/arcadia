<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBoardAndTaskRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ボード-タスクターブルを削除
        Schema::dropIfExists('board_task');
        // タスクテーブルに結合
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id')->after('id');
            $table->text('layout')->nullable()->after('color');

            $table->foreign('board_id')
                ->references('id')
                ->on('boards')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // タスクテーブルの変更を戻す
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->dropColumn(['board_id', 'layout']);
        });

        // ボード-タスクテーブルを作成
        Schema::create('board_task', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id');
            $table->unsignedBigInteger('task_id');
            $table->text('layout');
            $table->timestamps();

            $table->foreign('board_id')
                ->references('id')
                ->on('boards')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
        });
    }
}
