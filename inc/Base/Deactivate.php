<?php
/**
 * @package WordSteem
 */

namespace Inc\Base;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

class Deactivate 
{
 	public static function deactivate() 
 	{
 		flush_rewrite_rules();
 	}
 }