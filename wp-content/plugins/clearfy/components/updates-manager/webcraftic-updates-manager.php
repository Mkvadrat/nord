<?php
	/**
	 * Plugin Name: Webcraftic Updates manager
	 * Plugin URI: https://wordpress.org/plugins/webcraftic-updates-manager/
	 * Description: Manage all your WordPress updates, automatic updates, logs, and loads more.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.1
	 * Text Domain: webcraftic-updates-manager
	 * Domain Path: /languages/
	 */

	if( defined('WBCR_UPM_PLUGIN_ACTIVE') || (defined('WBCR_CLEARFY_PLUGIN_ACTIVE') && !defined('LOADING_UPDATES_MANAGER_AS_ADDON')) ) {
		function wbcr_upm_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - disable unused features" plugin installed, this plugin already has update manager functions, so you can deactivate plugin "Update manager"!'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_upm_admin_notice_error');

		return;
	} else {

		define('WBCR_UPM_PLUGIN_ACTIVE', true);

		define('WBCR_UPM_PLUGIN_DIR', dirname(__FILE__));
		define('WBCR_UPM_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WBCR_UPM_PLUGIN_URL', plugins_url(null, __FILE__));

		

		if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
			require_once(WBCR_UPM_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		function wbcr_upm_plugin_init()
		{
			global $wbcr_update_services_plugin;

			// Localization plugin
			load_plugin_textdomain('webcraftic-updates-manager', false, dirname(WBCR_UPM_PLUGIN_BASE) . '/languages/');

			if( defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
				//return;
				global $wbcr_clearfy_plugin;
				$wbcr_update_services_plugin = $wbcr_clearfy_plugin;
			} else {

				$wbcr_update_services_plugin = new Factory326_Plugin(__FILE__, array(
					'name' => 'wbcr_updates_manager',
					'title' => __('Webcraftic Updates Manager', 'webcraftic-updates-manager'),
					'version' => '1.0.1',
					'host' => 'wordpress.org',
					'url' => 'https://wordpress.org/plugins/webcraftic-updates-manager/',
					'assembly' => 'free',
					'updates' => WBCR_UPM_PLUGIN_DIR . '/updates/'
				));

				// requires factory modules
				$wbcr_update_services_plugin->load(array(
					array('libs/factory/bootstrap', 'factory_bootstrap_330', 'admin'),
					array('libs/factory/forms', 'factory_forms_329', 'admin'),
					array('libs/factory/pages', 'factory_pages_324', 'admin'),
					array('libs/factory/clearfy', 'factory_clearfy_102', 'all')
				));
			}

			// loading other files
			if( is_admin() ) {
				require_once(WBCR_UPM_PLUGIN_DIR . '/admin/boot.php');
			}

			require(WBCR_UPM_PLUGIN_DIR . '/includes/classes/class.configurate-updates.php');
			new WbcrUpm_ConfigUpdates($wbcr_update_services_plugin);
		}

		if( defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
			wbcr_upm_plugin_init();
		} else {
			add_action('plugins_loaded', 'wbcr_upm_plugin_init');
		}
	}