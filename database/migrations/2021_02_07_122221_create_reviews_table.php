<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           2021_02_07_122221_create_reviews_table.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     07/02/2021, 9:33 PM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		 Schema::create('reviews', function (Blueprint $table) {
			 $table->id();
             $table->uuid('uuid')->nullable();
             $table->string('slug')->nullable();
			 $table->string('title')->nullable();
			 $table->integer('score');
			 $table->text('comment')->nullable();
			 $table->tinyInteger('status')->default(0);
			 $table->morphs('author');
			 $table->morphs('reviewable');
			 $table->timestamps();

		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
