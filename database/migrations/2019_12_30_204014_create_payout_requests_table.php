<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           2019_12_30_204014_create_payout_requests_table.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     16/02/2021, 11:59 AM
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutRequestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payout_requests', function (Blueprint $table) {
			$table->id();
            $table->uuid('uuid')->nullable();
            $table->string('slug')->nullable();
			$table->unsignedBigInteger('withdrawal_setup_id')->nullable();
			$table->morphs('ownerable');
			$table->float('amount', 10, 2)->default(0.00);
			$table->float('final_amount', 10, 2)->default(0.00);
			$table->tinyInteger('status')->default(0)->comment('0:pending 1:approved 2:declined');
			$table->tinyInteger('completed')->default(0);
			$table->dateTime('completed_at')->nullable();
			$table->string('decline_reason')->nullable();
			$table->dateTime('approved_at')->nullable();
			$table->dateTime('declined_at')->nullable();
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
		Schema::dropIfExists('payout_requests');
	}
}
