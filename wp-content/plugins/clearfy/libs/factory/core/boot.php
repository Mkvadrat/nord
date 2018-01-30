<?php
	/**
	 * Factory Plugin
	 *
	 * @author Paul Kashtanoff <paul@byonepress.com>, Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2013, OnePress Ltd, (c) 2017 Webcraftic Ltd
	 *
	 * @package core
	 * @since 1.0.1
	 */

	if( defined('FACTORY_326_LOADED') ) {
		return;
	}
	define('FACTORY_326_LOADED', true);

	define('FACTORY_326_VERSION', '000');

	define('FACTORY_326_DIR', dirname(__FILE__));
	define('FACTORY_326_URL', plugins_url(null, __FILE__));

	#comp merge
	require(FACTORY_326_DIR . '/includes/assets-managment/assets-list.class.php');
	require(FACTORY_326_DIR . '/includes/assets-managment/script-list.class.php');
	require(FACTORY_326_DIR . '/includes/assets-managment/style-list.class.php');

	require(FACTORY_326_DIR . '/includes/functions.php');
	require(FACTORY_326_DIR . '/includes/plugin.class.php');

	require(FACTORY_326_DIR . '/includes/activation/activator.class.php');
	require(FACTORY_326_DIR . '/includes/activation/update.class.php');
	#endcomp
