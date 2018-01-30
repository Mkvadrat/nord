<?php
	/**
	 * Factory clearfy
	 *
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017 Webcraftic Ltd
	 *
	 * @package core
	 * @since 1.0.0
	 */

	if( defined('FACTORY_CLEARFY_102_LOADED') ) {
		return;
	}
	define('FACTORY_CLEARFY_102_LOADED', true);

	define('FACTORY_CLEARFY_102_DIR', dirname(__FILE__));
	define('FACTORY_CLEARFY_102_URL', plugins_url(null, __FILE__));

	load_plugin_textdomain('factory_clearfy_102', false, dirname(plugin_basename(__FILE__)) . '/langs');

	require(FACTORY_CLEARFY_102_DIR . '/includes/functions.php');
	require(FACTORY_CLEARFY_102_DIR . '/includes/class.configurate.php');

	// module provides function only for the admin area
	if( !is_admin() ) {
		return;
	}

	if( defined('FACTORY_PAGES_324_LOADED') ) {
		require(FACTORY_CLEARFY_102_DIR . '/pages/more-features.php');
	}