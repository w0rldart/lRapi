<?php

class Places_List {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('places_list', function($table)
		{
			$table->increments('id');
			$table->string('version', 3);
			$table->text('list');
			$table->string('hash', 40);
			$table->date('created_at');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('places_list');
	}

}