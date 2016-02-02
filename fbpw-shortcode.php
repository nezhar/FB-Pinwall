<?php
defined('ABSPATH') or die("No script kiddies please!");

add_shortcode( 'fbpw', 'fbpw_func' );

function fbpw_func($atts) {
    $options = shortcode_atts(array(
        'fb-pages' => FB_PAGES,
        'num-posts' => NUM_POST
    ), $atts);

	//Get Data from Facebook feeds
	$feed = get_fb_feed_data($options['fb-pages'], $options['num-posts']);

	//Create HTML of the feed Data
	$html = feed_to_html($feed);

	//Replace Shortcode
	return $html;
}


function get_fb_feed_data($fb_pages = FB_PAGES, $num_posts = NUM_POST) {

	/* TO DO
	define('Repeater', '');
	Items per feed
	*/

	//FB Pages for feed
	$profile_ids = explode(",", $fb_pages);

	//Check if a feed Already Exists
	//Get Feed from file or create a feed file and pass data
	$feedHash = substr(md5($fb_pages), 0, 8).".txt";

	//App Info, needed for Auth
	$app_id = APP_ID;
	$app_secret = APP_SECRET;

	//retrieve auth token
	$authToken = file_get_contents("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id={$app_id}&client_secret={$app_secret}");

	$feed = array();

	foreach ($profile_ids as $profile_id) {
		//retrive data
		$data = file_get_contents("https://graph.facebook.com/{$profile_id}/photos/uploaded?{$authToken}&limit=".$num_posts);
		$images = json_decode($data);

		foreach ($images->data as $image) {

			$wp_upload_dir = wp_upload_dir('fbpw');

			//Create a custom Thumbnail
			$thumb = 'thumb_'.md5($image->images[0]->source).".".end(explode('.', strtok($image->images[0]->source, '?')));
			if (!file_exists($wp_upload_dir['path']."/".$thumb)) {
				make_thumb($image->images[0]->source, $wp_upload_dir['path']."/".$thumb, 250);
			}

			$feed[] = array(
				'title' => $image->from->name,
				'thumb' => $wp_upload_dir['url']."/".$thumb,
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
