<?php

class Device extends Eloquent {

	public static $table = 'user_devices';
	public static $key   = 'device_id';

	public function users()
	{
		return $this->has_many_and_belongs_to('User');
	}

}
