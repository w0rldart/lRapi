<?php

class Api_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();

		$input = Input::all();

		$this->_log_entries($input);

		$header_part = isset(Request::headers()[Config::get('lrapi_config.authorize')]) ? Request::headers()[Config::get('lrapi_config.authorize')] : null;
		$is_special = (isset($header_part) && strpos($header_part[0], 'true') !== false);
		$is_special = true;

		if( $is_special )
		{
			if(strpos($header_part[0], "token"))
				$this->_check_token();

			return;
		}
		else
		{
			$this->_check_mobile();

			$key = Config::get('lrapi_config.api_key', null);

			if (isset($input['device_id']))
			{
				if ( URI::segment(2) != 'device')
				{
					$this->_check_token();
					$this->_check_hash();
				}
				else
				{
					if( ( ! isset($input['key'])) && ($key !== (isset($input['key']) ? $input['key'] : null)) )
					{
						$response['error'] = array(
							'type'    => 'invalid_credentials',
							'message' => 'Key not set, or invalid',
						);
					}
				}
			}
			else
			{
				$response['error'] = array(
					'type'    => 'missing_data',
					'message' => 'Device id not set, or invalid',
				);
			}

			if(isset($response['error']))
				die(Response::json($response));
		}
	}

	private function _log_entries($input)
	{
		$log_requests             = new LogRequests;
		
		$log_requests->url        = Request::uri();
		$log_requests->input      = json_encode($input);
		$log_requests->headers    = json_encode(Request::headers());
		$log_requests->ip         = Request::ip();
		$log_requests->created_at = new \DateTime;;

		if ( ! $log_requests->save())
		{
			$response['error'] = array(
				'type'    => 'db_insert_error',
				'message' => 'There was an issue inserting logs in the database',
			);

			if(isset($response['error']))
				die(Response::json($response));
		}
	}

	private function _check_mobile()
	{
		if( ! Holmes::is_mobile() )
		{
			$response['error'] = array(
				'type'    => 'mobile_only',
				'message' => 'Requests allowed only from mobile devices',
			);

			if(isset($response['error']))
				die(Response::json($response));
		}
	}

	private function _check_token()
	{
		if( ! isset(Request::headers()['x-authorization'][0]))
		{
			$response['error'] = array(
				'type'    => 'missing_data',
				'message' => 'No token provided!',
			);
		}
		else
		{
			if( Device::where_api_token(Request::headers()['x-authorization'][0])->first() == NULL )
			{
				$response['error'] = array(
					'type'    => 'invalid_credentials',
					'message' => 'Invalid authentication credentials!',
				);
			}
		}
			
		if(isset($response['error']))
			die(Response::json($response));
	}

	private function _check_hash()
	{
		$input = Input::all();
		
		if(isset($input['hash']))
		{
			$input_hash = $input['hash'];
			$input = array_except($input, 'hash');
			ksort($input);
			
			$api_key      = Device::find($input['device_id'])->api_key;
			$input_string = implode('', $input);

			$hash = base64_encode(hash_hmac('sha1', $input_string, $api_key, TRUE));

			if($hash !== $input_hash)
			{
				$response['error'] = array(
					'type'    => 'invalid_credentials',
					'message' => 'Hash check failed!',
				);
			}
		}
		else
		{
			$response['error'] = array(
				'type'    => 'missing_data',
				'message' => 'Hash not set!',
			);			
		}
			
		if(isset($response['error']))
			die(Response::json($response));
	}

}