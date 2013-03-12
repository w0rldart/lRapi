<?php

class Activation extends Eloquent {

	public static $timestamps = false;

	public function users()
	{
		return $this->has_one('User');
	}

}
