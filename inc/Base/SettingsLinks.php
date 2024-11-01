<?php
/**
 * @package WordSteem
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

class SettingsLinks extends BaseController
{

	public function register() 
	{
		add_filter("plugin_action_links_$this->plugin_name", array($this, 'settings_link'));
	}


 	public static function settings_link($links) 
 	{
 		$settings_link = '<a href="admin.php?page=wordsteem">Settings</a>';
 		array_push($links, $settings_link);
 		return $links;
 	}
 }