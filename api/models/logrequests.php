<?php

class LogRequests extends Eloquent {

	public static $table = 'lrapi';
	public static $timestamps = false;
	public static $connection = 'logs';
	
}