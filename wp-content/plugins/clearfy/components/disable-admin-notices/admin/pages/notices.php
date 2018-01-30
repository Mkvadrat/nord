<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	class WbcrHan_NoticesPage extends FactoryPages324_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages324_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "notices";
		public $page_menu_dashicon = 'dashicons-testimonial';

		public function __construct(Factory326_Plugin $plugin)
		{
			$this->menuTitle = __('Hide admin notices', 'disable-admin-notices');

			if( !defined('LOADING_DISABLE_ADMIN_NOTICES_AS_ADDON') ) {
				$this->internal = false;
				$this->menuTarget = 'options-general.php';
				$this->addLinkToPluginActions = true;
			}

			add_filter('wbcr_factory_imppage_actions_notice', array($this, 'actionsNotice'), 10, 2);

			parent::__construct($plugin);
		}

		public function getMenuTitle()
		{
			return defined('LOADING_DISABLE_ADMIN_NOTICES_AS_ADDON')
				? __('Notices', 'disable-admin-notices')
				: __('General', 'disable-admin-notices');
		}


		/**
		 * We register notifications for some actions
		 * @param $notices
		 * @param $plugin
		 * @return array
		 */
		public function actionsNotice($notices, $plugin)
		{
			global $wbcr_dan_plugin;

			if( $wbcr_dan_plugin->pluginName != $plugin->pluginName ) {
				return $notices;
			}

			$notices[] = array(
				'conditions' => array(
					'wbcr_dan_reseted_notices' => 1
				),
				'type' => 'success',
				'message' => __('Hidden notices are successfully reset, now you can see them again!', 'disable-admin-notices')
			);

			/*$notices[] = array(
				'conditions' => array(
					'wbcr_dan_clear_comments_error' => 1,
					'wbcr_dan_code' => 'interal_error'
				),
				'type' => 'danger',
				'message' => __('An error occurred while trying to delete comments. Internal error occured. Please try again later.', 'factory_pages_324')
			);*/

			return $notices;
		}

		/**
		 * Permalinks options.
		 *
		 * @since 1.0.0
		 * @return mixed[]
		 */
		public function getOptions()
		{
			$options = wbcr_dan_get_plugin_options();

			$formOptions = array();

			$formOptions[] = array(
				'type' => 'form-group',
				'items' => $options,
				//'cssClass' => 'postbox'
			);

			return apply_filters('wbcr_dan_notices_form_options', $formOptions, $this);
		}
	}

	FactoryPages324::register($wbcr_dan_plugin, 'WbcrHan_NoticesPage');
