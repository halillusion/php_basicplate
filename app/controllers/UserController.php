<?php

namespace app\controllers;

/**
 * 
 * Database Model
 * 
 **/
use app\models\UserModel as Model;

class UserController extends Model {

	function __construct() {
		
	}

	function view ($key = null) {

		global $pageStructure, $title;

		$title = $key;

		if (is_array($pageStructure)) {
			
			foreach ($pageStructure as $part) {

				if ($part == '_') { // Content File

					require path('app/views/user/'.$key.'.php');

				} elseif (file_exists(path('app/views/'.$part.'.php'))) { // Layout File

					require path('app/views/'.$part.'.php');

				}

			}
			
		}

	}

}