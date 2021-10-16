<?php

namespace app\core\helpers;

use app\core\Database;

class Session {

	protected $authCode = null;

	function __construct() {

		$this->authCode = isset($_COOKIE[config('app.session')]) !== false ? 
			$_COOKIE[config('app.session')] : null;

	}

	/**
	 *	@param array 
	 * 	@return void
	 **/
	public function create (object $data) {

		if (! $this->authCode) return;

		$_SESSION['user'] = $data;
		$_SESSION['auth'] = true;

		// Clear older sessions
		(new Database)
			->table('sessions')
			->where('auth_code', $this->authCode)
			->delete();

		// Create fresh session
		(new Database)
			->table('sessions')
			->insert([
				'auth_code'			=> $this->authCode,
				'user_id'			=> $data->id,
				'header'			=> getHeader(),
				'ip'				=> getIP(),
				'role_id'			=> $data->role_id,
				'last_action_date'	=> time(),
				'last_action_point'	=> $_SERVER['REQUEST_URI']
			]);

		return true;

	}

	/**
	 * 	@return boolean
	 **/
	public function isLogged () {

		if (! $this->authCode) return false;

		$session = (new Database)
			->table('sessions')
			->where('auth_code', $this->authCode)
			->get();

		return $session ? true : false;

	}

}