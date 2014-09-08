<?php


use lithium\action\Dispatcher;
use lithium\security\Auth;
use app\models\Users;


define('LI3_AUTH_PATH', dirname(__DIR__));


Dispatcher::applyFilter('run', function($self, $params, $chain) {

	//	Set up authenticated user
	$auth = Auth::check('default');
	if ($auth) {
		$params['request']->auth = Users::findById($auth['id']);
	}

	$response = $chain->next($self, $params, $chain);
    return $response;

});



// use lithium\net\http\Media;

// Media::type('default', null, array(
//     'view' => 'lithium\template\View',
//     'paths' => array(
//         'layout' => array(
//         	LITHIUM_APP_PATH . '/views/layouts/{:layout}.{:type}.php',
//             '{:library}/views/layouts/{:layout}.{:type}.php'
//         ),
//         'template' => array(
//         	// LITHIUM_APP_PATH . '/views/{:controller}/{:template}.{:type}.php',
//             '{:library}/views/{:controller}/{:template}.{:type}.php'
//         ),
//         'element'  => array(
//         	LITHIUM_APP_PATH . '/views/{:controller}/_{:template}.{:type}.php',
//             LITHIUM_APP_PATH . '/views/elements/{:template}.{:type}.php',
//             '{:library}/views/{:controller}/_{:template}.{:type}.php',
//             '{:library}/views/elements/{:template}.{:type}.php'
//         )
//     )
// ));

	
?>