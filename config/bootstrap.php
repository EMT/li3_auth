<?php


use lithium\action\Dispatcher;
use lithium\security\Auth;


Dispatcher::applyFilter('run', function($self, $params, $chain) {

	//	Set up authenticated user
	$auth = Auth::check('default');
	if ($auth) {
		$params['request']->auth = Users::findById($auth['id']);
	}

	$response = $chain->next($self, $params, $chain);
    return $response;

});



use lithium\net\http\Media;

Media::type('default', null, array(
    'view' => 'lithium\template\View',
    'paths' => array(
        'layout' => array(
        	LITHIUM_APP_PATH . '/views/layouts/{:layout}.{:type}.php',
            '{:library}/views/layouts/{:layout}.{:type}.php'
        ),
        'template' => array(
        	LITHIUM_APP_PATH . '/views/{:controller}/{:template}.{:type}.php',
            '{:library}/views/{:controller}/{:template}.{:type}.php'
        ),
        'element'  => array(
        	LITHIUM_APP_PATH . '/views/{:controller}/_{:template}.{:type}.php',
            LITHIUM_APP_PATH . '/views/elements/{:template}.{:type}.php',
            '{:library}/views/{:controller}/_{:template}.{:type}.php',
            '{:library}/views/elements/{:template}.{:type}.php'
        )
    )
));




/**
 * Add the below to app/config/bootstrap/session.php
 * (or any application bootstrap file)
 * and edit the configuration settings
 */

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