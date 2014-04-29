<?php

$routes = array(
	'/search' => array('plugin' => 'i18n_search', 'controller' => 'search',
		'action' => 'show'),
	'/search/*' => array('plugin' => 'i18n_search', 'controller' => 'search',
		'action' => 'show'),
);

foreach ($routes as $url => $route) {
	CroogoRouter::connect($url, $route);
	if (CakePlugin::loaded('Translate')) {
		// If using Translate, this will also promote the localized route added
		// by CroogoRouter::connect
		Router::promote();
	}
	Router::promote(); // Put last connected route on top of the route's array
}
