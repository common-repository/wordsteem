<?php
/**
 * @package WordSteem
 */

namespace Inc\Pages;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;
use \Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
	public $settings;
	public $pages;
	public $sub_pages;

	public function register() 
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this
			->settings
			->addPages($this->pages)
			->withSubPage('Settings')
			->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'WordSteem',
				'menu_title' => 'WordSteem',
				'capability' => 'manage_options',
				'menu_slug' => 'wordsteem',
				'callback' => array($this->callbacks, 'adminDashboard'),
				'icon_url' => 'dashicons-money',
				'position' => 110
			)
		);
	}
	
	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'wordsteem_options_group',
				'option_name' => 'username'
			),
			array(
				'option_group' => 'wordsteem_options_group',
				'option_name' => 'posting_key'
			),
			array(
				'option_group' => 'wordsteem_options_group',
				'option_name' => 'default_tags'
			),
			array(
				'option_group' => 'wordsteem_options_group',
				'option_name' => 'error_reporting_consent'
			)
		);

		$this->settings->setSettings($args);
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'wordsteem_admin_index',
				'title' => 'Settings',
				'callback' => array($this->callbacks, 'wordsteemAdminSection'),
				'page' => 'wordsteem'
			)
		);

		$this->settings->setSections($args);
	}

	public function setFields()
	{
		$args = array(
			array(
				'id' => 'username',
				'title' => 'Username',
				'callback' => array($this->callbacks, 'wordsteemUsername'),
				'page' => 'wordsteem',
				'section' => 'wordsteem_admin_index',
				'args' => array(
					'label_for' => 'username'
				)
			),
			array(
				'id' => 'posting_key',
				'title' => 'Private posting key',
				'callback' => array($this->callbacks, 'wordsteemPrivatePostingKey'),
				'page' => 'wordsteem',
				'section' => 'wordsteem_admin_index',
				'args' => array(
					'label_for' => 'posting_key'
				)
			),
			array(
				'id' => 'default_tags',
				'title' => 'Default tags (will be added as default in post)',
				'callback' => array($this->callbacks, 'wordsteemDefaultTags'),
				'page' => 'wordsteem',
				'section' => 'wordsteem_admin_index',
				'args' => array(
					'label_for' => 'default_tags'
				)
			),
			array(
				'id' => 'error_reporting_consent',
				'title' => 'Send error reports in order to support WordSteem development',
				'callback' => array($this->callbacks, 'wordsteemErrorReportingConsent'),
				'page' => 'wordsteem',
				'section' => 'wordsteem_admin_index',
				'args' => array(
					'label_for' => 'error_reporting_consent'
				)
			)
		);

		$this->settings->setFields($args);
	}
}