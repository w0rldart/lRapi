<?php

class Response extends \Laravel\Response {

	/**
	 * Create a new JSON response.
	 *
	 * <code>
	 *		// Create a response instance with JSON
	 *		return Response::json($data, 200, array('header' => 'value'));
	 * </code>
	 *
	 * @param  mixed     $data
	 * @param  int       $status
	 * @param  array     $headers
   	 * @param  int       $json_options
	 * @return Response
	 */
	public static function json($data, $status = 200, $headers = array(), $json_options = 0)
	{
		$headers['Content-Type'] = 'application/json; charset=utf-8';

	    if ( (isset($data['error'])) && (isset($data['error']['type'])) )
	    {
	    	$status = Config::get('lrapi_status.'.$data['error']['type']);
	    }
	    elseif (isset($data['error']))
	    {
	    	$status = 400;
	    }

	    http_response_code($status);

		return new static(json_encode($data, $json_options), $status, $headers);
	}

	/**
	 * Create a new response instance.
	 *
	 * <code>
	 *		// Create a response instance with string content
	 *		return Response::make(json_encode($user));
	 *
	 *		// Create a response instance with a given status
	 *		return Response::make('Not Found', 404);
	 *
	 *		// Create a response with some custom headers
	 *		return Response::make(json_encode($user), 200, array('header' => 'value'));
	 * </code>
	 *
	 * @param  mixed     $content
	 * @param  int       $status
	 * @param  array     $headers
	 * @return Response
	 */
	public static function make($content, $status = 200, $headers = array())
	{
		return new static($content, $status, $headers);
	}

}