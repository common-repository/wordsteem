<?php
/**
 * @package WordSteem
 */

namespace Inc\MetaBoxes;

defined('ABSPATH') or die('Sorry, you can\'t access this file');

use \Inc\Base\BaseController;
use \Inc\Entity\SteemPost;

class Post extends BaseController
{
	public function register()
	{
		add_action('add_meta_boxes', array($this, 'addMetaBox'));
		add_action( 'transition_post_status', array($this, 'wordsteemSaveFormData'), 10, 3 );
	}

	public function addMetaBox() 
	{
		add_meta_box('wordsteem', 'WordSteem', array($this, 'displayPostSettings'), 'post', 'advanced', 'high');
	}

	public function displayPostSettings($post) 
	{
		wp_nonce_field('wordsteemSaveFormData', 'wordsteem_meta_box_nonce');
		$isPublished = (int) get_post_meta($post->ID, '_wordsteem_is_published_key', true);

		$username = esc_attr(get_option('username'));
		$reward = get_post_meta($post->ID, '_wordsteem_reward_key', true);
		$permalink = get_post_meta($post->ID, '_wordsteem_permalink_key', true);
		$tags = get_post_meta($post->ID, '_wordsteem_tags_key', true);

		$subtitle = $username . ' in <span id="wordsteem-live-preview-category" class="wordsteem-bold">wordsteem</span> * 0 seconds ago';

		$defaultTags = esc_attr(get_option('default_tags'));
		if ($isPublished) {
			$username = get_post_meta($post->ID, '_wordsteem_username_key', true);

			if ($reward == 100) {
				$reward = 'Power Up 100%';
			} else if ($reward == 50) {
				$reward = 'Default (50% / 50%)';
			} else {
				$reward = 'Decline Payout';
			}

			echo '<p class="wordsteem-meta-box__published-box"><span style="background-image: url(' . $this->plugin_url . 'assets/check.svg)"></span>Post is published on Steemit</p><br/>';
			echo '<div class="wordsteem-meta-box__published-details">';
				echo 'Published under username: ' . $username . '<br/>';
				echo 'Published with tags: ' . $tags . '<br/>';
				echo 'Reward selected: '. $reward . '<br/>';
				echo '<a href="https://steemit.com/@' . $username . '/' . $permalink . '" target="_blank">View on Steemit</a>';
			echo '</div>';
			return;
		}

		echo '<div class="wordsteem-meta-box row">';

		echo '<p id="wordsteem-username-hidden">' . $username . '</p>';

		echo '<div class="wordsteem-meta-box__half">';

		echo 
			'
			<div class="wordsteem-meta-box__row">
				<label class="wordsteem-meta-box__checkbox-label" for="wordsteem_to_publish">Do you want to publish this post to Steemit?</label>
				<input 
					type="checkbox" id="wordsteem_to_publish" name="wordsteem_to_publish" checked />
			</div>';

		echo
    	'
    	<div class="wordsteem-meta-box__row">
				<label for="wordsteem_tags">Default tags</label>
				<p class="wordsteem__field-explanation">First tag will be the category of the post.</p>
		    <input id="wordsteem_tags" name="wordsteem_tags" type="text" value="' . $defaultTags . '" />
	    </div>';

    echo
    	'
    	<div class="wordsteem-meta-box__row">
	    	<label for="wordsteem_permalink">Permalink (post link)</label>
		    <input id="wordsteem-permalink" name="wordsteem_permalink" type="text" value="' . $permalink . '" />
	    </div>';

		echo 
			'
			<div class="wordsteem-meta-box__row">
				<label for="wordsteem_reward">Select Reward</label>
		    <select name="wordsteem_reward" id="wordsteem_reward">
		        <option value="100">Power Up 100%</option>
						<option value="50">Default (50% / 50%)</option>
						<option value="0">Decline Payout</option>
		    </select>
	    </div>';

    echo '</div>'; // close wordsteem-meta-box__half class div


   	echo '<div class="wordsteem-meta-box__half wordsteem-meta-box__live-preview-container">';

   	echo '<div class="wordsteem-live-preview">';

   	echo '<p id="wordsteem-live-preview-link" class="wordsteem-live-preview__link"></p>';
		 
		echo '<div class="wordsteem-live-preview__title-container">';
			echo '<p id="wordsteem-live-preview-title" class="wordsteem-live-preview__title">Wordsteem post title</p>';
			echo '<span class="wordsteem-live-preview__title-icon" style="background-image: url(' . $this->plugin_url . 'assets/steemit.svg)"></span>';
		echo '</div>';

		echo '<div class="wordsteem-live-preview__subtitle"><span class="wordsteem-live-preview__user-pic"></span>' . $subtitle . '</div>';

   	echo '<div class="wordsteem-live-preview__body-line"></div>';
   	echo '<div class="wordsteem-live-preview__body-line"></div>';
   	echo '<div class="wordsteem-live-preview__body-line"></div>';
   	echo '<div class="wordsteem-live-preview__body-line"></div>';

   	echo '<div id="wordsteem-live-preview-tags" class="wordsteem-live-preview__tags"></div>';

   	echo '</div>';

   	echo '</div>'; // close wordsteem-meta-box__live-preview class div

    echo '</div>'; // close wordsteem-plugin class div
	}

	public function wordsteemSaveFormData($new_status, $old_status, $post)
	{

		if ( $new_status !== 'publish' ) {
			return;
		}

		$wasPublishedBefore = (int) get_post_meta($post->ID, '_wordsteem_is_published_key', true);

		if ($wasPublishedBefore) {
			return;
		}

		if (!isset($_POST['wordsteem_meta_box_nonce'])) {
			return;
		}

		if (!wp_verify_nonce($_POST['wordsteem_meta_box_nonce'], 'wordsteemSaveFormData')) {
			return;
		}

		if (wp_is_post_autosave($post->ID)) {
			return;
		}

		if (!current_user_can('edit_post', $post->ID)) {
			return;
		}

		if (!isset($_POST['wordsteem_to_publish'])) {
			update_post_meta($post->ID, '_wordsteem_to_publish_key', 'no');
			return;
		}

		$reward = sanitize_text_field($_POST['wordsteem_reward']);
		$tags = sanitize_text_field($_POST['wordsteem_tags']);
		$permalink = sanitize_text_field($_POST['wordsteem_permalink']);

		$steemPost = new SteemPost($post, $tags, $permalink, $reward);
		$steemPost->publish();
	}
}