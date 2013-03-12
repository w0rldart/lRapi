<?php

class Places {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('places', function($table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->string('name', 60);

			$table->integer('type')->unsigned();
			$table->foreign('type')->references('id')->on('places_types');

			$table->float('lat');
			$table->float('lng');

			$table->integer('code'); // Place code
			
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
		Schema::drop('places');
	}

}