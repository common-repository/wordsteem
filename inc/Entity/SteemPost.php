<?php
/**
 * @package WordSteem
 */

namespace Inc\Entity;

use \Inc\Base\Notices;
use \Inc\Base\BaseController;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

class SteemPost extends BaseController
{
	public $reward;
	public $tags;
	public $permalink;
	public $wpPost;

	public function __construct($wpPost, $tags, $permalink, $reward)
	{
		parent::__construct();
		$this->wpPost = $wpPost;
		$this->tags = $tags;
		$this->permalink = $permalink;
		$this->reward = $reward;
	}

	public function publish()
	{
		$postTitle = $this->wpPost->post_title;
		$postBody = $this->wpPost->post_content;
		$featuredImage = null;

		if (has_post_thumbnail( $this->wpPost->ID )) {
			$featuredImage = '<img src="' . wp_get_attachment_image_src( get_post_thumbnail_id( $this->wpPost->ID ), 'single-post-thumbnail' )[0] . '"/><br/><br/>';
		}

		if (!$this->tags || ctype_space($this->tags)) {
			$this->tags = null;
		}

		$this->tags = trim($this->tags);

		$data = array(
			'username' => esc_attr(get_option('username')),
			'postingKey' => esc_attr(get_option('posting_key')),
			'postTitle' => $postTitle,
			'postBody' => $featuredImage ? $featuredImage . $postBody : $postBody,
			'tags' => empty($this->tags) ? '' : explode(' ', $this->tags),
			'permalink' => $this->slugify($this->permalink),
			'reward' => $this->reward
		);

		$url = $this->api_url . '/post';

		try {
			$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'body' => $data,
				'timeout' => 45
				)
			);

			$notices = new Notices();
			
			if (is_wp_error($response)) {
				$this->reportError($response);
				$notices->notify('WordSteem Error: ' . ' the was an error with the service. Please try again later.', 'notice-error');
				return;
			}

			$jsonResponse = json_decode($response['body'], true);
	
			if ($jsonResponse['success']) {
				$notices->notify('WordSteem Success: ' . $jsonResponse['message'], 'notice-success');

				update_post_meta($this->wpPost->ID, '_wordsteem_is_published_key', 1);
				update_post_meta($this->wpPost->ID, '_wordsteem_reward_key', $this->reward);
				update_post_meta($this->wpPost->ID, '_wordsteem_username_key', esc_attr(get_option('username')));
				update_post_meta($this->wpPost->ID, '_wordsteem_permalink_key', $this->permalink);
				update_post_meta($this->wpPost->ID, '_wordsteem_tags_key', $this->tags);

			} else {
				$errorMessage = $jsonResponse['message'];
				if (!$errorMessage || trim($errorMessage) == '') {
					$errorMessage = 'Problem with the service, please try again later.';
				}
				$notices->notify('WordSteem Error: ' . $errorMessage, 'notice-error');
			}

		} catch (Exception $e) {
			$this->reportError($e->getMessage());
			$notices->notify('WordSteem Error: ' . ' the was an error with the service. Please try again later. Error: ' . $e->getMessage(), 'notice-error');
		}
	}

	public function slugify($text)
	{
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  $text = preg_replace('~[^-\w]+~', '', $text);
	  $text = trim($text, '-');
	  $text = preg_replace('~-+~', '-', $text);
	  $text = strtolower($text);

	  if (empty($text)) {
	    return 'n-a';
	  }

	  return $text;
	}

	private function reportError($message) {
		if (esc_attr(get_option('error_reporting_consent')) == 'on') {
			wp_remote_post( "$this->api_url/error-report", array(
				'method' => 'POST',
				'body' => array(
						'message' => $message
					)
				)
			);
		}
	}
}