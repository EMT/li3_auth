<?php

use lithium\net\http\Router;


/**
 * Session routes
 */
Router::connect('/login', 'Sessions::add');
Router::connect('/logout', 'Sessions::delete');

?>