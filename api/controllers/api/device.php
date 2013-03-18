<?php

class Api_Device_Controller extends Api_Controller {

	/**
     * Controller is RESTful.
     *
     * @var bool
     */
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
	}

	/**
     * Insert device in the database
     *
     * @return string
     */
	public function post_index()
	{
		$post = Input::all();

		if (Device::where('device_id', '=', $post['device_id'])->first())
		{
			$response['key']   = Device::find($post['device_id'])->api_key;
			$response['token'] = Device::find($post['device_id'])->api_token;
			$response['status'] = 'existing';
		}
		else
		{
			$device = new Device;

			$device->device_id = $post['device_id'];

			$api_key   = $device->api_key   = Str::random(64);
			$api_token = $device->api_token = Str::random(128);

			if ( ! $device->save())
			{
				$response['key']   = $api_key;
				$response['token'] = $api_token;
				$response['status'] = 'new';
			}
			else
			{
				$response['error'] = array(
					'type' => 'db_insert_error',
					'message' => 'An error occured while trying to register the device.',
				);
			}
		}

        return Response::json($response);
	}
}