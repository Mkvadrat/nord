<?php
	/**
	 * Plugin Name: Webcraftic Assets manager
	 * Plugin URI: https://wordpress.org/plugins/gonzales/
	 * Description: Increase the speed of the pages by disabling unused scripts (.JS) and styles (.CSS). Make your website REACTIVE!
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.1
	 * Text Domain: gonzales
	 * Domain Path: /languages/
	 */

	if( defined('WBCR_GNZ_PLUGIN_ACTIVE') || (defined('WBCR_CLEARFY_PLUGIN_ACTIVE') && !defined('LOADING_GONZALES_AS_ADDON')) ) {
		function wbcr_gnz_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you use the plugin "Clearfy - disable unused functions", this plugin already has the same functions as "Assets manager", so you can disable the "Assets manager" plugin!', 'gonzales'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_gnz_admin_notice_error');

		return;
	} else {

		define('WBCR_GNZ_PLUGIN_ACTIVE', true);

		define('WBCR_GNZ_PLUGIN_DIR', dirname(__FILE__));
		define('WBCR_GNZ_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WBCR_GNZ_PLUGIN_URL', plugins_url(null, __FILE__));

		

		if( !defined('LOADING_GONZALES_AS_ADDON') ) {
			require_once(WBCR_GNZ_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		function wbcr_gnz_plugin_init()
		{
			global $wbcr_gonzales_plugin;

			// Localization plugin
			load_plugin_textdomain('gonzales', false, dirname(WBCR_GNZ_PLUGIN_BASE) . '/languages/');

			if( defined('LOADING_GONZALES_AS_ADDON') ) {
				//return;
				global $wbcr_clearfy_plugin;
				$wbcr_gonzales_plugin = $wbcr_clearfy_plugin;
			} else {

				$wbcr_gonzales_plugin = new Factory326_Plugin(__FILE__, array(
					'name' => 'wbcr_gonzales',
					'title' => __('Webcraftic assets manager', 'gonzales'),
					'version' => '1.0.1',
					'host' => 'wordpress.org',
					'url' => 'https://wordpress.org/plugins/gonzales/',
					'assembly' => 'free',
					'updates' => WBCR_GNZ_PLUGIN_DIR . '/updates/'
				));

				// requires factory modules
				$wbcr_gonzales_plugin->load(array(
					array('libs/factory/bootstrap', 'factory_bootstrap_330', 'admin'),
					array('libs/factory/forms', 'factory_forms_329', 'admin'),
					array('libs/factory/pages', 'factory_pages_324', 'admin'),
					array('libs/factory/clearfy', 'factory_clearfy_102', 'all')
				));
			}

			// loading other files
			if( is_admin() ) {
				require(WBCR_GNZ_PLUGIN_DIR . '/admin/boot.php');
			}

			require(WBCR_GNZ_PLUGIN_DIR . '/includes/class.configurate-assets.php');
			new WbcrGnz_ConfigAssetsManager($wbcr_gonzales_plugin);
		}

		if( defined('LOADING_GONZALES_AS_ADDON') ) {
			wbcr_gnz_plugin_init();
		} else {
			add_action('plugins_loaded', 'wbcr_gnz_plugin_init');
		}
	}

