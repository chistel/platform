<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferringLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referring_levels', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->boolean('is_intro')->default(false);
            $table->unsignedBigInteger('next_level_id')->nullable();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->integer('min_referral')->default(0);
            $table->integer('max_referral')->default(0)->nullable();
            $table->decimal('percentage',10,4)->default(0);
            $table->decimal('percentage_value_cap',10,4)->default(0);
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
        Schema::dropIfExists('referring_levels');
    }
}
