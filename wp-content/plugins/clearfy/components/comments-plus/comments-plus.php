<?php
	/**
	 * Plugin Name: Webcraftic Disable Comments
	 * Plugin URI: https://wordpress.org/plugins/comments-plus/
	 * Description: Allows administrators to globally disable comments on their site. Comments can be disabled for individual record types.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.5
	 * Text Domain: comments-plus
	 * Domain Path: /languages/
	 */

	if( defined('WBCR_CMP_PLUGIN_ACTIVE') || (defined('WBCR_CLEARFY_PLUGIN_ACTIVE') && !defined('LOADING_COMMENTS_PLUS_AS_ADDON')) ) {
		function wbcr_cmp_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - disable unused features" plugin installed, this plugin already has disable comments functions, so you can deactivate plugin "Disable comments"!'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_cmp_admin_notice_error');

		return;
	} else {

		define('WBCR_CMP_PLUGIN_ACTIVE', true);

		define('WBCR_CMP_PLUGIN_DIR', dirname(__FILE__));
		define('WBCR_CMP_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WBCR_CMP_PLUGIN_URL', plugins_url(null, __FILE__));

		

		if( !defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
			require_once(WBCR_CMP_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		function wbcr_cmp_plugin_init()
		{
			global $wbcr_comments_plus_plugin;

			// Localization plugin
			load_plugin_textdomain('comments-plus', false, dirname(WBCR_CMP_PLUGIN_BASE) . '/languages/');

			if( defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
				//return;
				global $wbcr_clearfy_plugin;
				$wbcr_comments_plus_plugin = $wbcr_clearfy_plugin;
			} else {

				$wbcr_comments_plus_plugin = new Factory326_Plugin(__FILE__, array(
					'name' => 'wbcr_comments_plus',
					'title' => __('Webcraftic Disable comments', 'comments-plus'),
					'version' => '1.0.5',
					'host' => 'wordpress.org',
					'url' => 'https://wordpress.org/plugins/comments-plus/',
					'assembly' => 'free',
					'updates' => WBCR_CMP_PLUGIN_DIR . '/updates/'
				));

				// requires factory modules
				$wbcr_comments_plus_plugin->load(array(
					array('libs/factory/bootstrap', 'factory_bootstrap_330', 'admin'),
					array('libs/factory/forms', 'factory_forms_329', 'admin'),
					array('libs/factory/pages', 'factory_pages_324', 'admin'),
					array('libs/factory/clearfy', 'factory_clearfy_102', 'all')
				));
			}

			// loading other files
			if( is_admin() ) {
				require(WBCR_CMP_PLUGIN_DIR . '/admin/boot.php');
			}

			require(WBCR_CMP_PLUGIN_DIR . '/includes/classes/class.configurate-comments.php');

			new WbcrCmp_ConfigComments($wbcr_comments_plus_plugin);
		}

		if( defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
			wbcr_cmp_plugin_init();
		} else {
			add_action('plugins_loaded', 'wbcr_cmp_plugin_init');
		}
	}