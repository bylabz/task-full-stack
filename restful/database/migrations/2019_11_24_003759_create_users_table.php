<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('username', 60)->unique();
			$table->string('name', 255);
			$table->string('email', 255);
			$table->string('password', 255);
			$table->string('phone', 25)->nullable();
			$table->string('website',	60)->nullable();
			$table->string('addr_street', 160)->nullable();
			$table->string('addr_suite', 60)->nullable();
			$table->string('add_city', 60)->nullable();
			$table->string('add_zip', 50)->nullable();
			$table->float('add_geo_lat')->nullable();
			$table->float('add_geo_lng')->nullable();
			$table->string('cpn_name', 120)->nullable();
			$table->text('cpn_phrase')->nullable();
			$table->text('cpn_bs')->nullable();
			$table->string('api_token', 255)->nullable();
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
		Schema::dropIfExists('users');
	}
}
