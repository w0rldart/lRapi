<?php

class Places_Task {

    /**
     * Simply echos out some help info.
     *
     */
    public function help() { $this->run(); }
    public function run()
    {
        echo <<<EOT
\n=============PLACES COMMANDS=============\n        
places:list_all
places:show [version]
places:create_list
places:creat_place [name] [type] [address] [lat] [lng] [city] [code]
\n=====================END====================\n
EOT;
    }

	public function list_all()
	{
		$raw_places = Place_List::get(array('version', 'list'));

		$places_list = array();
		$i = 0;
		foreach ($raw_places as $each_place)
		{
			foreach ($each_place->attributes as $key => $value)
			{
				$places_list[$i][$key] = $value;
			}
			$i++;
		}
		print_r($places_list);
	}

	public function create_list()
	{
		if ( ! $raw_places = Place::get(array('id', 'name', 'type', 'address', 'lat', 'lng', 'code')))
		{
			echo "\nError: There are no places in the database!\n";
			$this->help();
		}
		else
		{
			$places = array();
			$i = 0;
			foreach ($raw_places as $each_place)
			{
				foreach ($each_place->attributes as $key => $value)
				{
					$places[$i][$key] = $value;
				}
				$i++;
			}

			$places_encoded = serialize($places);
			$places_hashed  = sha1($places_encoded);

			$places_list = Place_List::where('hash', '=', $places_hashed)->first();
			$new_list = new Place_List;

			if($places_list)
			{
				echo "\nThere is already a list containing the actual places!\n";
				return;
			}
			else
			{
				$last_version = Place_List::order_by('id', 'desc')->take(1)->only('version');

				$increment = '001';
				$result = sprintf('%03d', $last_version + $increment);

				$new_list->version    = $result;
				$new_list->list       = $places_encoded;
				$new_list->hash       = $places_hashed;
				$new_list->created_at = new \DateTime;

				if( ! $new_list->save())
				{
					echo "\nError: There was an issue create the new places list\n";
					return;
				}
				else
				{
			        echo <<<EOT
\n/*
/** places list created
/*
Version: $result
Hash: $new_list->hash \n
EOT;
				}
			}
		}
	}

	/**
	 * Generate a place
	 *
	 * USAGE:
	 *
	 * php artisan places:creat_place [name] [type] [address] [lat] [lng] [city] [code]
	 *
	 * @param $args array
	 * @return string
	 */
	public function create_place($args)
	{
		if ( empty($args) ) {
            echo "\nError: Please supply the required parameters.\n";
            echo "Usage: places:creat_place [name] [type] [address] [lat] [lng] [city] [code] \n";
            return;
        }

		$new_place = new Place;

		$new_place->name    = $args[0];
		$new_place->type    = $args[1];
		$new_place->address = $args[2];
		$new_place->lat     = $args[3];
		$new_place->lng     = $args[4];
		$new_place->city    = $args[5];
		$new_place->code    = $args[6];

		if( ! $new_place->save())
		{
			echo "Error: Couldn't save the place, please try again!";
			return;
		}
		else
		{
			$params = implode(', ', $args);
			echo "\nA new place has been created with the following parameters: $params\n";
		}
	}
}