<?php
	/**
	 * Plugin Name: Webcraftic Disable Admin Notices Individually
	 * Plugin URI: https://wordpress.org/plugins/disable-admin-notices/
	 * Description: Disable admin notices plugin gives you the option to hide updates warnings and inline notices in the admin panel.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.2
	 * Text Domain: disable-admin-notices
	 * Domain Path: /languages/
	 */

	if( defined('WBCR_DAN_PLUGIN_ACTIVE') || (defined('WBCR_CLEARFY_PLUGIN_ACTIVE') && !defined('LOADING_DISABLE_ADMIN_NOTICES_AS_ADDON')) ) {
		function wbcr_dan_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - disable unused features" plugin installed, this plugin already has disable comments functions, so you can deactivate plugin "Disable admin notices"!'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_dan_admin_notice_error');

		return;
	} else {

		define('WBCR_DAN_PLUGIN_ACTIVE', true);

		define('WBCR_DAN_PLUGIN_DIR', dirname(__FILE__));
		define('WBCR_DAN_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WBCR_DAN_PLUGIN_URL', plugins_url(null, __FILE__));

		

		if( !defined('LOADING_DISABLE_ADMIN_NOTICES_AS_ADDON') ) {
			require_once(WBCR_DAN_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		function wbcr_dan_plugin_init()
		{
			global $wbcr_dan_plugin;

			// Localization plugin
			load_plugin_textdomain('disable-admin-notices', false, dirname(WBCR_DAN_PLUGIN_BASE) . '/languages/');

			if( defined('LOADING_DISABLE_ADMIN_NOTICES_AS_ADDON') ) {
				//return;
				global $wbcr_clearfy_plugin;
				$wbcr_dan_plugin = $wbcr_clearfy_plugin;
			} else {

				$wbcr_dan_plugin = new Factory326_Plugin(__FILE__, array(
					'name' => 'wbcr_dan',
					'title' => __('Webcraftic disable admin notices', 'disable-admin-notices'),
					'version' => '1.0.0',
					'host' => 'wordpress.org',
					'url' => 'https://wordpress.org/plugins/disable-admin-notices/',
					'assembly' => 'free',
					'updates' => WBCR_DAN_PLUGIN_DIR . '/updates/'
				));

				// requires factory modules
				$wbcr_dan_plugin->load(array(
					array('libs/factory/bootstrap', 'factory_bootstrap_330', 'admin'),
					array('libs/factory/forms', 'factory_forms_329', 'admin'),
					array('libs/factory/pages', 'factory_pages_324', 'admin'),
					array('libs/factory/clearfy', 'factory_clearfy_102', 'all')
				));
			}

			// loading other files
			if( is_admin() ) {
				require(WBCR_DAN_PLUGIN_DIR . '/admin/boot.php');
			}

			require(WBCR_DAN_PLUGIN_DIR . '/includes/classes/class.configurate-notices.php');
			new WbcrHan_ConfigHideNotices($wbcr_dan_plugin);
		}

		if( defined('LOADING_DISABLE_ADMIN_NOTICES_AS_ADDON') ) {
			wbcr_dan_plugin_init();
		} else {
			add_action('plugins_loaded', 'wbcr_dan_plugin_init');
		}
	}