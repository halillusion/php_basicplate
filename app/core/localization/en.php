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
		'user_not_found'					=> 'There is no such account!',
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
		'this_account_already_verified'		=> 'This account has already been verified.',
		'your_account_has_been_verified'	=> 'The account has been successfully verified.',
		'your_account_could_not_be_verified'=> 'There was a problem verifying the account!',
		'cant_send_recover_link_unverified'	=> 'We can\'t send the recovery link to accounts without email verification.',
		'failed_send_password_reset_link'	=> 'Failed to send password reset link.',
		'sent_password_reset_link'			=> 'Password reset link has been sent.',
		'cant_reset_password_unverified'	=> 'Unverified accounts cannot reset passwords.',
		'cant_change_email_unverified'		=> 'Unverified accounts cannot update their email addresses.',
		'cant_change_password_unverified'	=> 'Unverified accounts cannot update their passwords.',
		'password_has_been_reset'			=> 'Your password has been successfully reset.',
		'failed_resetting_password'			=> 'There was a problem resetting the password.',
		'no_changes_to_save'				=> 'No changes to save!',
		'profile_updated'					=> 'Your account has been successfully updated.',
		'failed_profile_updated'			=> 'There was a problem updating your account.',
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
		'recover_title'						=> 'Account Recovery',
		'recover_mail_body'					=> 'Hi [USER], <br>
		We just received an account recovery request. If this process belongs to you, you can renew your password using the link below. <br>
		<a href="[LINK]">Renew password.</a>',
		'recover_completed_title'			=> 'Your Account Has Been Recovered!',
		'recover_completed_mail_body'		=> 'Hi [USER], <br>
		Your password has been successfully reset. If the transaction is not yours, please get in touch.',
		'verify_email_title'				=> 'Verify Email Address',
		'verify_email_mail_body'			=> 'Hi [USER], <br>
		Email verification required. You can do this by clicking the link below. <br>
		<a href="[LINK]">Verify now.</a>',	
	],

	/**
	 *  Page definitions
	 * 
	 **/
	'page'	=> [
		'index'								=> 'Home',
		'index_desc'						=> 'Home description.',
		'contact'							=> 'Contact',
		'contact_desc'						=> 'Contact page.',
		'login'								=> 'Login',
		'login_desc'						=> 'Login page.',
		'register'							=> 'Register',
		'register_desc'						=> 'Register page.',
		'recover'							=> 'Account Recovery',
		'recover_desc'						=> 'Account recovery page.',
		'account'							=> 'Account',
		'account_desc'						=> 'Account page.',
		'dashboard'							=> 'Dashboard',
		'dashboard_desc'					=> 'Dashboard home page.',
	],

	/**
	 *  Other definitions
	 * 
	 **/
	'def'	=> [
		'home'								=> 'Home',
		'welcome'							=> 'Welcome',
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
		'verify'							=> 'Verify',
		'token'								=> 'Token',
		'verify_desc'						=> 'You can verify your account by pasting the token in the field below.',
		'recover_desc'						=> 'You can reset your account password from this page.',
		'new_password'						=> 'New Password',
		'management'						=> 'Management',
	],

];