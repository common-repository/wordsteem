<?php
/**
 * @package WordSteem
 */

namespace Inc\Api\Callbacks;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminDashboard()
	{
		return require_once("$this->plugin_path/templates/admin.php");
	}

	public function wordsteemOptionsGroup($input)
	{
		return $input;
	}

	public function wordsteemAdminSection()
	{
	}

	public function wordsteemUsername()
	{
		$value = esc_attr(get_option('username'));
		echo '<input type="text" class="regular-text" name="username" value="' . $value . '" placeholder="Write your username here">';
	}

	public function wordsteemPrivatePostingKey()
	{
		$value = esc_attr(get_option('posting_key'));
		echo '<input type="text" class="regular-text" name="posting_key" value="' . $value . '" placeholder="Write your private posting key here">';
	}

	public function wordsteemDefaultTags()
	{
		$value = esc_attr(get_option('default_tags'));
		echo '<input type="text" class="regular-text" name="default_tags" value="' . $value . '" placeholder="Default tags">';
	}

	public function wordsteemErrorReportingConsent()
	{
		$value = esc_attr(get_option('error_reporting_consent'));
		$checkboxValue = '';

		if ($value == 'on') {
			$checkboxValue = 'checked';
		}
		
		echo '<input type="checkbox" name="error_reporting_consent" ' . $checkboxValue . '/>';
	}
}