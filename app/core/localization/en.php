<?php

// PHP Basicplate - English Translation

return [

	/**
	 *  Basic language definitions
	 * 
	 **/
	'app' => [
		'lang_code'							=> 'en',
		'lang_bigcode'						=> 'en-US',
		'lang_name'							=> 'English',
		'locale'							=> 'en_US.UTF-8',
		'timezone'							=> 'America/New_York',
		'charset'							=> 'UTF-8',
		'dir'								=> 'ltr',
		'plural_suffix'						=> '',
	],

	/**
	 *  Basic alert and message definitions
	 * 
	 **/
	'alert' => [
		'error'								=> 'Error.',
		'warning'							=> 'Warning.',
		'success'							=> 'Successful.',
		'form_cannot_empty'					=> 'Form cannot be submitted empty!',
		'you_have_an_session'				=> 'You have an session!',
		'you_have_not_an_session'			=> 'You haven\'t an session!',
		'account_not_found'					=> 'No such account found!',
		'user_info_incorrect'				=> 'Your account information is incorrect!',
		'you_are_logged_in'					=> 'You are logged in!',
		'you_are_logged_out'				=> 'You are logged out!',
		'session_create_fail'				=> 'Failed to create session!',
		'a_problem_occurred'				=> 'A problem occurred!',
		'form_token_invalid'				=> 'The form token is invalid.',
		'not_allowed_request'				=> 'Not allowed request!',
		'contact_form_saved'				=> 'We have successfully received your message!',
		'contact_form_not_saved'			=> 'There was a problem retrieving your message!',
		'already_created_this_username'		=> 'There is already an account with this username.',
		'already_created_this_email'		=> 'There is already an account with this email address.',
		'your_account_has_been_created'		=> 'Your account has been successfully created!',
		'your_account_could_not_be_created'	=> 'Your account could not be created!',
	],

	/**
	 *  Notification definitions
	 * 
	 **/
	'notification' => [
		'register_title'					=> 'Your account has been created!',
		'register_mail_body'				=> 'Hi [USER], <br>
		Your account has been successfully created. You can verify your account with the following methods. <br>
		<a href="[LINK]">Verify now.</a>',	
	],

	/**
	 *  Other definitions
	 * 
	 **/
	'def'	=> [
		'home'								=> 'Home',
		'login'								=> 'Login',
		'logout'							=> 'Logout',
		'register'							=> 'Register',
		'recover'							=> 'Recover Account',
		'login_desc'						=> 'You can login with your username or e-mail address.',
		'email_or_username'					=> 'E-mail or Username',
		'password'							=> 'Password',
		'loading'							=> 'Loading...',
		'contact'							=> 'Contact',
		'account'							=> 'Account',
		'username'							=> 'Username',
		'email'								=> 'Email Address',
		'first_name'						=> 'Name',
		'last_name'							=> 'Surname',
		'birth_date'						=> 'Birth Date',
		'password'							=> 'Password',
		'update'							=> 'Update',
		'subject'							=> 'Subject',
		'send'								=> 'Send',
		'contact_type'						=> 'Contact Type',
		'other'								=> 'Other',
		'bug_report'						=> 'Bug Report',
		'suggestion'						=> 'Suggestion',
		'message'							=> 'Message',
	],

];