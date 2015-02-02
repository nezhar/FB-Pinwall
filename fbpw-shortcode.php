<?php
defined('ABSPATH') or die("No script kiddies please!");

add_shortcode( 'fbpw', 'fbpw_func' );

function fbpw_func() {

	//Get Data from Facebook feeds
	$feed = get_fb_feed_data();

	//Create HTML of the feed Data
	$html = feed_to_html($feed);

	//Replace Shortcode
	return $html;	
}


function get_fb_feed_data() {

	/* TO DO
	define('Repeater', '');
	Items per feed
	*/

	//FB Pages for feed
	$profile_ids = explode(",",FB_PAGES);

	//App Info, needed for Auth
	$app_id = APP_ID;
	$app_secret = APP_SECRET;

	//retrieve auth token
	$authToken = file_get_contents("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id={$app_id}&client_secret={$app_secret}");

	$feed = array();

	foreach ($profile_ids as $profile_id) {
		//retrive data
		$data = file_get_contents("https://graph.facebook.com/{$profile_id}/photos/uploaded?{$authToken}&limit=50");
		$images = json_decode($data);

		foreach ($images->data as $image) {

			$feed[] = array(
				'title' => $image->from->name,
				'thumb' => $image->images[3]->source,
				'src' => $image->images[0]->source,
				'created' => strtotime($image->created_time),
				'target_blank' => '1',
			);
		}
	}

	return $feed;
}

function feed_to_html(array $feed) {

	$output = "<div class='fbpw'>";

	foreach ($feed as $data) {
		$output .= "<a href='{$data['src']}' class='fbpw-image'>";
		$output .= "<img src='{$data['thumb']}'>";
		$output .= "</a>";
	}

	$output .= "</div> <div style='clear:both;'></div>";

	return $output;
}