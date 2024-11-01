<?php
/**
 * @package WordSteem
 */

namespace Inc\Base;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{

	public function register() 
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}

	public function enqueue() 
	{
		wp_enqueue_style('pluginstyle', $this->plugin_url . '/assets/style.css');
		wp_enqueue_script('pluginscript', $this->plugin_url . '/assets/script.js');
	}
 }