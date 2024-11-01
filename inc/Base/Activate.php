<?php
/**
 * @package WordSteem
 */

namespace Inc\Base;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

class Activate 
{

	public static function activate() 
	{
		flush_rewrite_rules();
	}
 }