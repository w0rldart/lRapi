<?php

class Api_User_Controller extends Api_Controller {

	/**
     * Controller is RESTful.
     *
     * @var bool
     */
	public $restful = true;

	public function __construct()
	{
		parent::__construct();

		$this->response    = array();
		$this->internal    = false;
		$this->validate_id = null;
	}

	/**
	 * Retrieve user data
	 *
	 * @param integer $id
	 * @return Response 
	 */
	public function get_data($id = null)
	{
		if($id)
		{
			$user = User::find($id);
			$this->response['user_data'] = $user;
		}
		else
		{
			$response['error'] = array(
				'type' => 'missing_data',
				'message' => 'No such id',
			);
		}

        return Response::json($this->response, $this->status);
	}

	/**
	 * Register new user
	 *
	 * @return Response 
	 */
	public function post_index()
	{
		$user = Input::all();

		/**
		* I need my precious device_id, can't live without it!
		*/
		if (Device::where('device_id', '=', $user['device_id'])->first())
		{
			/**
			 * Check if there isn't any existing user with that mail
			 */
	        if ( ! User::where('email', '=', $user['email'])->first())
	    	{
	    		$new = new User;

	    		$new->email = $user['email'];
	    		$new->name = $user['name'];
	    		
	    		/*
	    		 * Check for facebook token, and if not, for password
	    		 */
	    		if(isset($user['token_facebook']))
	    		{
					$new->token_facebook = $user['token_facebook'];
					$new->password = null;
	    		}
	    		else
	    		{
					$new->password = Hash::make($user['password']);
	    		}

	    		/*
	    		 * Create user
	    		 */
		        if($new->save())
		        {
		        	$user['id'] = $new->attributes['id'];
					$user['activation_hash'] = Str::random(30);

		        	$this->response = array(
	            		'user_id' => $user['id'],
	            	);

		        	if ( ! isset($user['token_facebook']))
		        	{
						$user_activation = new Activation;
						$user_activation->user_id = $user['id'];
						$user_activation->hash = $user['activation_hash'];

						if ($user_activation->save())
						{
							if( ! Device::update($user['device_id'], array('user_id'=>$user['id'])))
							{
			                    $this->response['error'] = array(
			                    	'type' => 'db_insert_error',
			                    	'message' => 'Can\'t assign user_id to device',
			                    );
							}

							Session::put('user', $user);

			                Message::send(function($mail)
			                {
			                    $user = Session::get('user');

			                    $mail->to($user['email']);
			                    $mail->from(Config::get('lrapi_config.mail.email', null), Config::get('lrapi_config.mail.name', null));

								$mail->subject('Confirm user registration');
			                    $mail->body('view: response.default');

			                    $mail->body->name = $user['name'];

			                    $mail->body->link = URL::to("activate/user/{$user['id']}/{$user['activation_hash']}");

			                    $mail->html(true);

			                    Session::forget('user');
			                });

			                if( ! Message::was_sent())
			                {
			                    $this->response['error'] = array(
			                    	'type' => 'internal',
			                    	'message' => 'Couldn\'t send the confirmation email'
			                    );		                	
			                }
			            }
						else
						{
							$this->response['error'] = array(
								'type' => 'db_insert_error',
								'message' => 'Couldn\'t generate the confirmation parameters for the account',
							);
						}
					}
					else
					{
						if ( ! User::where_id($user['id'])->update(array('active'=>1)))
						{
							$this->response['error'] = array(
								'type' => 'db_insert_error',
								'message' => 'Account couldn\'t be activated, please try ',
							);
						}
					}
		        }
		        else
		        {
		        	$this->response['error'] = array(
						'type' => 'db_insert_error',
						'message' => 'There was an error while creating the account, please try again!',
					);
		        }
		    }
		    else
		    {
				$this->response['error'] = array(
					'type' => 'register_failed',
					'message' => 'There is already a user registered, with those credentials.',
				);
		    }
		}
	    else
	    {
			$this->response['error'] = array(
				'type' => 'register_failed',
				'message' => 'No such device id registered',
			);
	    }

        return Response::json($this->response);
	}

	/**
	 * Check user's credentials on login
	 *
	 * @return Response 
	 */
	public function post_login()
	{
		$user = Input::all();

		if ($id = User::where_email($user['email'])->only('id'))
		{
			$this->internal = true;
			$this->validate_id = $id;
			if ($this->post_validate())
			{
				$this->response = array('user_id'=>$id);

				if(isset($user['token_facebook']) && ! isset($user['password']))
				{
					$attempt = User::where('email', '=', $user['email'])
									->where('token_facebook', '=', $user['token_facebook'])
									->first();
				}
				else
				{
					$attempt = Auth::attempt(
						array(
							'username' => $user['email'],
							'password' => $user['password'],
						)
					);
				}

		        if( ! $attempt)
		        {
		        	$this->response['error'] = array(
						'type'    => 'login_failed',
						'message' => 'Password or Email, invalid!',
		    		);
		        }
			}
			else
			{
				$this->response['error'] = array(
					'type'    => 'login_failed',
					'message' => 'Please activate your account before trying to log in',
	    		);
			}
		}
		else
		{
			$this->response['error'] = array(
				'type'    => 'login_failed',
				'message' => 'Password or Email, invalid!',
    		);
		}

        return Response::json($this->response);
	}

	public function post_validate()
	{
		$input = Input::all();
		$id = isset($input['user_id']) ? $input['user_id'] : $this->validate_id;

		if (( ! empty($id)) && (is_numeric($id)))
		{
			if( ! User::where_id($id)->only('active'))
			{
	    		if($this->internal)
	    			return false;
	    		else
					$this->response['error'] = array(
						'type'    => 'login_failed',
						'message' => 'Please activate your account before trying to log in',
		    		);
	    			return Response::json($this->response);
			}
			else
			{
	    		if($this->internal)
	    			return true;
	    		else
	    			return Response::json($this->response, 200);
			}
		}
		else
		{
			die(Response::json(array('message'=>'invalid id')));
		}
	}

	public function post_logout()
	{
		$input  = Input::all();
		
		$device = Device::find($input['device_id']);
		
		$device->api_key = Str::random(64);
		$device->api_token = Str::random(128);

		if ($device->save())
		{
			$this->response = array();
		}
		else
		{
			$this->response['error'] = array(
				'type'    => 'db_insert_error',
				'message' => 'Error regenerating keys for the device',
			);
		}
		return Response::json($this->response);
	}
}
