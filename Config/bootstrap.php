<?php
/**
 * Routes
 *
 * i18n_search_routes.php will be loaded in main app/config/routes.php file.
 */
Croogo::hookRoutes('I18nSearch');

/**
 * Behavior
 *
 * This plugin's Example behavior will be attached whenever Node model is loaded.
 */
//Croogo::hookBehavior('Node', 'Example.Example', array());

/**
 * Component
 *
 * This plugin's Example component will be loaded in ALL controllers.
 */
//Croogo::hookComponent('*', 'Example.Example');

// Configure CKeditor not to change utf-8 chars to html-entities
$l10n = new L10n();
$ckeditor_params = array(
	'elements' => 'NodeBody',
	'entities' => 0,
	'entities_greek' => 0,
	'entities_latin' => 0,
	'preset' => 'standard',
	//'language' => 'de',
	'language' => $l10n->map(Configure::read('Site.locale')),
	'uiColor' => '#ffe79a',
);
Croogo::mergeConfig('Wysiwyg.actions', array(
	'Nodes/admin_add' => array($ckeditor_params),
	'Nodes/admin_edit' => array($ckeditor_params),
	'Translate/admin_edit' => array($ckeditor_params),
));

