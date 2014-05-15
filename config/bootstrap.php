<?php

/**
 * Add the contents of this bootstrap file to app/config/bootstrap/session.php
 * (or any application bootstrap file)
 * and edit the configuration settings
 */

use lithium\security\Auth;
use lithium\security\Password;
use li3_auth\extensions\action\SessionsBaseController;


Auth::config([
 	'default' => [
 		'adapter' => 'Form',
 		'model' => 'Users',
 		'fields' => array('email', 'password'),
 		'validators' => [
 			'password' => function($form, $data) {
                return (trim($form)) && Password::check($form, $data);
            }
 		]
 	]
]);



SessionsBaseController::config([
	// Model to authenticate against
	'users_model' => 'app\models\Users',
	// Quick and dirty way to create an admin user for the application
	// This user is created and added to the database when login is attempted with these creds
	'super_admin' => [
		'email' => false, 
		'password' => 'something',
		// Add application specific fields
		'fname' => 'John',
		'lname' => 'Smith',
		'role' => 'sad',
		'terms' => 1,
		'verified' => 1
	],
	// Allow persistent sessions
	'persistent_sessions' => true
]);
	
?>