<?php

class User extends Eloquent {

	public function activation()
	{
		return $this->has_one('Activation');
	}

	public function devices()
	{
		return $this->has_many('Device');
	}

}