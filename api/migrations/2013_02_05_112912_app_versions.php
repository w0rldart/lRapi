<?php

class App_Versions {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('app_versions', function($table)
		{
			$table->increments('id');
			$table->string('version');
			$table->string('device');
			$table->string('key', 255);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('app_versions');
	}

}