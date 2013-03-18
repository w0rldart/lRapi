<?php

class Activate_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function action_index()
	{
		return Response::make('', 302, array('Location' => URL::home()));
	}

	public function action_user($id = null, $hash = null)
	{	
		if( ( ! is_null($id)) || ( ! is_null($hash)) )
		{
			if(Activation::where_user_id($id)->where_hash($hash)->first())
			{
				if(User::where_id($id)->update(array('active'=>1)))
				{
					Activation::where_user_id($id)->delete();
					$response = array(
						'message' => 'You\'ve succesfully activated your email. Thank you!',
					);
				}
				else
				{
					$response['error'] = array(
						'type' => 'db_insert_error',
						'message' => 'There was an issue activating your email, please try again later!',
					);
				}
			}
			else
			{
				$response = array(
					'message' => 'The user you\'re trying to activate, either was already activated or it doesn\'t exist!',
				);
			}
			return Response::make($response['message']);
		}
		else
		{
			return Response::error('404');
		}
		
	}

}