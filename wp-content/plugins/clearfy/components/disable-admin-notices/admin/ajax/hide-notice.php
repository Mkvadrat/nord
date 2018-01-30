<?php
	/**
	 * Hides notifications
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 12.01.2018, Webcraftic
	 * @version 1.0
	 */

	function wbcr_clearfy_ajax_hide_notices()
	{
		global $wbcr_dan_plugin;

		check_ajax_referer($wbcr_dan_plugin->pluginName . '_ajax_hide_notices_nonce', 'security');

		if( !current_user_can('manage_options') ) {
			echo json_encode(array('error' => __('You don\'t have enough capability to edit this information.', 'disable-admin-notices')));
			exit;
		}

		$notice_id = isset($_POST['notice_id'])
			? sanitize_text_field($_POST['notice_id'])
			: null;

		$notice_html = isset($_POST['notice_html'])
			? wp_kses($_POST['notice_html'], array(
				'a' => array(
					'href' => array()
				)
			))
			: null;

		if( empty($notice_id) ) {
			echo json_encode(array('error' => __('Undefinded notice id.', 'disable-admin-notices')));
			exit;
		}

		$get_hidden_notices = get_option($wbcr_dan_plugin->pluginName . '_hidden_notices');

		if( !is_array($get_hidden_notices) ) {
			$get_hidden_notices = array();
		}

		$get_hidden_notices[$notice_id] = rtrim(trim($notice_html));

		update_option($wbcr_dan_plugin->pluginName . '_hidden_notices', $get_hidden_notices);

		echo json_encode(array('success' => __('Success', 'disable-admin-notices')));
		exit;
	}

	add_action('wp_ajax_wbcr_clearfy_hide_notices', 'wbcr_clearfy_ajax_hide_notices');
