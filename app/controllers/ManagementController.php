<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\Core;

class ManagementController extends Core {

	function __construct() {
		
		$this->viewFolder = 'management';
		$this->titleBase = lang('def.management');

	}

}