<?php
/**
 * @package WordSteem
 */

namespace Inc;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

final class Init 
{
	/**
	 * Store all the classes inside an array
	 * @return array of classes
	 */
	public static function get_services() 
	{
		return [
			Pages\Admin::class,
			Base\Enqueue::class,
			Base\SettingsLinks::class,
			Base\Notices::class,
			MetaBoxes\Post::class
		];
	}

	/**
	 * Initialize classes, call register() if exists
	 * @return
	 */
	public static function register_services() 
	{
		foreach (self::get_services() as $class) {
			$service = self::instantiate($class);
			if (method_exists($service, 'register')) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param class $class 		class from the services array
	 * @return class instance   new instance of the class
	 */
	private static function instantiate($class) 
	{
		$service = new $class();

		return $service;
	}
}