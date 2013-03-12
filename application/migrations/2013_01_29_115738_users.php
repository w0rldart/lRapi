<?php

class Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('name', 255)->nullable();
			$table->string('email', 250);
			$table->string('password', 64)->nullable();
			$table->string('token_facebook', 255)->nullable();
			$table->boolean('active')->default(0);
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
		Schema::drop('users');
	}

}