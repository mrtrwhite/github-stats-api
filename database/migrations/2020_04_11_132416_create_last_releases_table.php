<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLastReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('last_releases', function (Blueprint $table) {
            $table->id();
            $table->integer('github_id');
            $table->string('name')->index();
            $table->string('tag_name');
            $table->text('url');
            $table->string('author_name');
            $table->datetime('date')->index();
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
        Schema::dropIfExists('last_releases');
    }
}
