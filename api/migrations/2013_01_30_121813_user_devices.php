<?php

class User_Devices {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_devices', function($table)
		{
			$table->engine = 'InnoDB';

			#$table->increments('id');
			
			$table->string('device_id')->primary();

			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');

			$table->boolean('verified')->default(0);
			$table->string('api_key')->nullable();
			$table->string('api_token')->nullable();
			
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_devices');
	}

}