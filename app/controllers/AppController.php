<?php

namespace app\controllers;

/**
 * 
 * Database Model
 * 
 **/
use app\models\AppModel as Model;

class AppController extends Model {

	function __construct() {
		
	}

	function view ($key = null) {

		require path('app/views/app/'.$key.'.php');

	}

}