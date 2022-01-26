<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           2018_07_20_054502_create_currencies_table.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     10/10/2020, 4:33 AM
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_currencies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('slug')->nullable();
            $table->string('code');
            $table->string('name');
            $table->string('name_plural')->nullable();
            $table->string('symbol')->nullable();
            $table->string('symbol_native')->nullable();
            $table->integer('decimal_digits')->default(0);
            $table->integer('rounding')->default(0);
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
        Schema::dropIfExists('all_currencies');
    }
}
