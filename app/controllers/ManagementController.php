<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\Core;

class ManagementController extends Core {

	function __construct() {
		
	}

	public function view ($key = null) {

		global $pageStructure, $title;

		$pageStructure = [
			'inc/header',
			'inc/management_nav',
			'_',
			'inc/footer',
			'inc/end',
		];

		$title = $key;

		if (is_array($pageStructure)) {
			
			if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
				echo '<title>'.title(false).'</title><body id="wrap">';
			}

			foreach ($pageStructure as $part) {

				if ($part == '_') { // Content File

					require path('app/views/management/'.$key.'.php');

				} elseif (file_exists(path('app/views/'.$part.'.php'))) { // Layout File

					if (
						isset($_SERVER['HTTP_X_VPJAX']) === false OR 
						in_array($part, ['inc/header', 'inc/end']) === false) {

						require path('app/views/'.$part.'.php');
					}

				}

			}

			if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
				echo '</body>';
			}
			
		}

	}

}