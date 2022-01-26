<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           2019_12_30_203736_create_payment_fractions_table.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/02/2021, 11:52 AM
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentFractionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_fractions', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->uuid('uuid')->nullable();
            $table->string('slug')->nullable();
			$table->morphs('ownerable');
			$table->morphs('sourceable');
			$table->float('amount', 15, 2)->default(0.00);
			$table->float('final_amount', 10, 2)->default(0.00);
			$table->enum('taken_commission_type', ['fixed', 'percent'])->default('percent');
			$table->float('taken_commission_value', 10, 2)->default(0.00);
			$table->unsignedBigInteger('payout_request_id')->nullable();
			$table->tinyInteger('can_be_requested')->default(0)->comment('0:No 1:Yes');
			$table->tinyInteger('status')->default(0)->comment('0:pending 1:approved 2:declined 3:refunded 4:cancelled');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('declined_at')->nullable();
            $table->dateTime('refunded_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
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
		Schema::dropIfExists('payment_fractions');
	}
}
