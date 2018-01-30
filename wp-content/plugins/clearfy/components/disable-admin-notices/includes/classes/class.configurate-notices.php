<?php
	
	/**
	 * This class configures hide admin notices
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017 Webraftic Ltd
	 * @version 1.0
	 */
	class WbcrHan_ConfigHideNotices extends WbcrFactoryClearfy_Configurate {

		public function registerActionsAndFilters()
		{
			if( is_admin() ) {
				$hide_notices_type = $this->getOption('hide_admin_notices');
				if( $hide_notices_type != 'not_hide' ) {
					add_action('admin_print_scripts', array($this, 'catchNotices'), 999);

					if( empty($hide_notices_type) || $hide_notices_type == 'only_selected' ) {
						add_action('admin_head', array($this, 'printNotices'), 999);
					}

					if( !empty($hide_notices_type) && $this->getOption('show_notices_in_adminbar') ) {
						add_action('admin_bar_menu', array($this, 'notificationsPanel'), 999);
						add_action('admin_enqueue_scripts', array($this, 'notificationsPanelStyles'));
					}
				}
			}
		}

		public function notificationsPanelStyles()
		{
			global $wbcr_dan_plugin;
			wp_enqueue_style('wpcm_admin', WBCR_DAN_PLUGIN_URL . '/admin/assets/css/notifications-panel.css', array(), $wbcr_dan_plugin->version);
		}

		public function notificationsPanel(&$wp_admin_bar)
		{
			global $wbcr_dan_plugin;

			$notifications = $this->getOption('hidden_notices');

			if( empty($notifications) ) {
				return;
			}

			$cont_notifications = sizeof($notifications);

			// Add top menu
			$wp_admin_bar->add_menu(array(
				'id' => 'wbcr-han-notify-panel',
				'parent' => 'top-secondary',
				'title' => sprintf(__('Notifications %s', 'disable-admin-notices'), '<span class="wbcr-han-adminbar-counter">' . $cont_notifications . '</span>'),
				'href' => false
			));

			// loop
			if( !empty($notifications) ) {
				$i = 0;
				foreach($notifications as $notification) {
					$wp_admin_bar->add_menu(array(
						'id' => 'wbcr-han-notify-panel-item-' . $i,
						'parent' => 'wbcr-han-notify-panel',
						'title' => $notification,
						'href' => false,
						'meta' => array(
							'class' => ''
						)
					));

					$i++;
				}
			}
		}

		public function printNotices()
		{
			add_action('admin_notices', array($this, 'noticesCollection'));
		}

		public function noticesCollection()
		{
			global $wbcr_dan_plugin_all_notices;

			if( empty($wbcr_dan_plugin_all_notices) ) {
				return;
			}
			?>
			<style>
				.wbcr-clearfy-hide-notices {
					position: relative;
					padding: 5px 5px 0;
					background: #fff;
				}

				.wbcr-clearfy-hide-notices > div {
					margin: 0 !important;
				}

				.wbcr-clearfy-hide-notice-link {
					display: block;
					text-align: right;
					margin: 5px 0 5px 5px;
					font-weight: bold;
					color: #F44336;
				}

				.is-dismissible .wbcr-clearfy-hide-notice-link {
					margin-right: -30px;
				}

				.wbcr-clearfy-hide-notice-link:active, .wbcr-clearfy-hide-notice-link:focus {
					box-shadow: none;
					outline: none;
				}
			</style>
			<script>
				var wbcr_clearfy_ajax_nonce = "<?=wp_create_nonce($this->plugin->pluginName . '_ajax_hide_notices_nonce')?>";
				var wbcr_clearfy_ajax_url = "<?=admin_url('admin-ajax.php')?>";

				jQuery(function() {
					jQuery(document).on('click', '.wbcr-clearfy-hide-notice-link', function() {
						var self = jQuery(this),
							noticeID = jQuery(this).data('notice-id'),
							noticeHtml = jQuery(this).closest('.wbcr-clearfy-hide-notices').clone();

						noticeHtml.find('.wbcr-clearfy-hide-notice-link').remove();

						if( !noticeID ) {
							alert('Undefinded error. Please report the bug to our support forum.');
						}

						jQuery.ajax(wbcr_clearfy_ajax_url, {
							type: 'post',
							dataType: 'json',
							data: {
								action: 'wbcr_clearfy_hide_notices',
								security: wbcr_clearfy_ajax_nonce,
								notice_id: noticeID,
								notice_html: noticeHtml.html()
							},
							success: function(data, textStatus, jqXHR) {
								if( data == 'error' && data.error ) {
									alert(data.error);
									return;
								}

								self.closest('.wbcr-clearfy-hide-notices').parent().hide();
							}
						});
					});
				});
			</script>
			<?php
			foreach($wbcr_dan_plugin_all_notices as $val) {
				echo $val;
			}
		}

		public function catchNotices()
		{
			global $wp_filter, $wbcr_dan_plugin_all_notices;

			$hide_notices_type = $this->getOption('hide_admin_notices');

			if( empty($hide_notices_type) || $hide_notices_type == 'only_selected' ) {
				$get_hidden_notices = $this->getOption('hidden_notices');

				$content = array();
				foreach($wp_filter['admin_notices']->callbacks as $filters) {
					foreach($filters as $callback_name => $callback) {

						if( 'usof_hide_admin_notices_start' == $callback_name || 'usof_hide_admin_notices_end' == $callback_name ) {
							continue;
						}

						ob_start();
						call_user_func_array($callback['function'], array());
						$cont = ob_get_clean();

						if( empty($cont) ) {
							continue;
						}

						$uniq_id1 = md5($cont);
						$uniq_id2 = md5($callback_name);

						if( is_array($callback['function']) && sizeof($callback['function']) == 2 ) {
							$class = $callback['function'][0];
							if( is_object($class) ) {
								$class_name = get_class($class);
								$method_name = $callback['function'][1];
								$uniq_id2 = md5($class_name . ':' . $method_name);
							}
						}

						if( !empty($get_hidden_notices) ) {

							$skip_notice = true;
							foreach((array)$get_hidden_notices as $key => $notice) {
								$splited_notice_id = explode('_', $key);
								if( empty($splited_notice_id) || sizeof($splited_notice_id) < 2 ) {
									continue;
								}
								$compare_notice_id_1 = $splited_notice_id[0];
								$compare_notice_id_2 = $splited_notice_id[1];

								if( $compare_notice_id_1 == $uniq_id1 || $compare_notice_id_2 == $uniq_id2 ) {
									$skip_notice = false;
								}
							}

							if( !$skip_notice ) {
								continue;
							}
						}

						$hide_link = '<a href="#" data-notice-id="' . $uniq_id1 . '_' . $uniq_id2 . '" class="wbcr-clearfy-hide-notice-link">[' . __('Hide notification forever', 'disable-admin-notices') . ']</a>';

						$cont = preg_replace('/<(script|style)([^>]+)?>(.*?)<\/(script|style)>/is', '', $cont);
						$cont = rtrim(trim($cont));
						$cont = preg_replace('/^(<div[^>]+>)(.*?)(<\/div>)$/is', '$1<div class="wbcr-clearfy-hide-notices">$2' . $hide_link . '</div>$3', $cont);

						if( empty($cont) ) {
							continue;
						}
						$content[] = $cont;
					}
				}

				$wbcr_dan_plugin_all_notices = $content;
			}

			if( is_user_admin() ) {
				if( isset($wp_filter['user_admin_notices']) ) {
					unset($wp_filter['user_admin_notices']);
				}
			} elseif( isset($wp_filter['admin_notices']) ) {
				unset($wp_filter['admin_notices']);
			}
			if( isset($wp_filter['all_admin_notices']) ) {
				unset($wp_filter['all_admin_notices']);
			}
		}
	}