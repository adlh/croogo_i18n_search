<?php
/**
 * I18nSearch Activation
 *
 * Activation class for I18nSearch plugin.
 * This is optional, and is required only if you want to perform tasks when your plugin is activated/deactivated.
 *
 * @package  Croogo
 * @author   Andrea de la Huerta <info@metamorfosys.de>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class I18nSearchActivation {

/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeActivation(&$controller) {
		return true;
	}

/**
 * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onActivation(&$controller) {
		// ACL: set ACOs with permissions
		$controller->Croogo->addAco('Search');
		$controller->Croogo->addAco('Search/show', array('registered', 'public'));
	}

/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
	public function beforeDeactivation(&$controller) {
		return true;
	}

/**
 * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onDeactivation(&$controller) {
		// ACL: remove ACOs with permissions
		$controller->Croogo->removeAco('Search');
		$controller->Croogo->removeAco('Search/show', array('registered', 'public'));

	}
}
