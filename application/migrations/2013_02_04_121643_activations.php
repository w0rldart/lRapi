<?php

class Activations {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activations', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('hash');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('activations');
	}

}