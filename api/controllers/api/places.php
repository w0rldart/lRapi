<?php

class Api_Place_Controller extends Api_Controller {

	/**
     * Controller is RESTful.
     *
     * @var bool
     */
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->response = array();
	}

	public function post_check()
	{
		$input = Input::all();
		if( (Places_List::where('version', '>', $input['version'])->first()) || ($input['version'] === '000') )
		{
			$raw_list = Places_List::order_by('version', 'desc')->take(1)->first();
			$this->response = array(
				'version' => $raw_list->attributes['version'],
				'list' => unserialize($raw_list->attributes['list']),
			);
			return Response::json($this->response, 201);
		}
		else
		{
			$this->response['error'] = array(
				'message' => 'You already have the latest version of the places list',
			);
			return Response::json($this->response, 200);
		}
	}

}