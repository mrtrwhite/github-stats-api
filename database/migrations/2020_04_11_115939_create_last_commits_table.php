<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLastCommitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('last_commits', function (Blueprint $table) {
            $table->id();
            $table->string('sha')->index();
            $table->string('commit_author_name');
            $table->datetime('commit_author_date')->index();
            $table->string('commit_committer_name');
            $table->datetime('commit_committer_date')->index();
            $table->string('message');
            $table->text('url');
            $table->bigInteger('repository_id')->unsigned();
            $table->timestamps();

            $table->foreign('repository_id')
                ->references('id')
                ->on('repositories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('last_commits');
    }
}
