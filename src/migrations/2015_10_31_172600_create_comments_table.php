<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

    public function __construct() {
        $this->tablename = config('comments.table');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::connection(config('comments.connection'))->create($this->tablename, function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->default(0)->index();
            $table->integer('user_id')->nullable()->default(0)->index();
            $table->integer('thread_id')->index();
            $table->string('namespace')->index();
            $table->text('content');
            $table->integer('point')->default(0);
            $table->boolean('approved')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::connection(config('comments.connection'))->drop($this->tablename);
    }

}
