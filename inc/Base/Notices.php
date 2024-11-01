<?php
/**
 * @package WordSteem
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

class Notices extends BaseController
{
  public $message;

	public function register() 
	{
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
  }

  public function notify($message, $noticeType)
  {
    update_option('wordsteem_notice_field', [
      'message' => $message,
      'noticeType' => $noticeType
    ]);

    add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
  }

  public function add_notice_query_var( $location ) {
    remove_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
    
    $location = add_query_arg(array(
			'wordsteem_notice' => 'notice',
		), $location);

    return $location;
   }
 
   public function admin_notices() {
    if ( ! isset( $_GET['wordsteem_notice'] ) ) {
      return;
    }
    
    $notice = get_option('wordsteem_notice_field');

    foreach ($notice as $key => $noticeField) {
      $notice[$key] = esc_attr($noticeField);
    }

    echo "<div class='notice {$notice['noticeType']} is-dismissible'><p>{$notice['message']}</p></div>";
   }
 }