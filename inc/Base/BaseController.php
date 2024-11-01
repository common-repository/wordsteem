<?php
/**
 * @package WordSteem
 */

namespace Inc\Base;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

class BaseController 
{
	public $plugin_path;
	public $plugin_url;
	public $plugin_name;
	public $api_url;

	public function __construct() {
		$this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
		$this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
		$this->plugin_name = plugin_basename(dirname(__FILE__, 3) . '/wordsteem.php');
		$this->api_url = 'https://wordsteem-api.herokuapp.com';
	} 	
}