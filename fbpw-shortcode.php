<?php
defined('ABSPATH') or die("No script kiddies please!");

add_shortcode( 'fbpw', 'fbpw_func' );

function fbpw_func($atts) {
    $options = shortcode_atts(array(
        'fb-pages' => FBPW_FB_PAGES,
        'num-posts' => FBPW_NUM_POST
    ), $atts);

    if (FBPW_APP_ID && FBPW_APP_SECRET) {
        try {
            //Get Data from Facebook feeds
        	$feed = fbpw_get_fb_feed_data($options['fb-pages'], $options['num-posts']);

        	//Create HTML of the feed Data
        	$html = fbpw_get_html_from_feed($feed);

            //Replace Shortcode
        	return $html;
        } catch (Exception $e) {
            return fbpw_error_message($e->getMessage());
        }
    } else {
        return fbpw_error_message('APP_ID and APP_SECRET are not configured');
    }
}

function fbpw_get_fb_feed_data($fb_pages = FBPW_FB_PAGES, $num_posts = FBPW_NUM_POST) {

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
	$app_id = FBPW_APP_ID;
	$app_secret = FBPW_APP_SECRET;

	//retrieve auth token
	$authToken = @file_get_contents("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id={$app_id}&client_secret={$app_secret}");
    if ($authToken === false) {
        throw new Exception("Could not create auth token. FBPW_APP_ID or FBPW_APP_SECRET might not be valid");
    }

	$feed = array();

	foreach ($profile_ids as $profile_id) {
		//retrive data
		$data = @file_get_contents("https://graph.facebook.com/{$profile_id}/photos/uploaded?{$authToken}&limit=".$num_posts);
        if ($data === false) {
            trigger_error("Could not fetch data. Invalid profile Id: {$profile_id}", E_USER_NOTICE);
        } else {
            $images = json_decode($data);
    		foreach ($images->data as $image) {

    			$wp_upload_dir = wp_upload_dir('fbpw');

    			//Create a custom Thumbnail
    			$thumb = 'thumb_'.md5($image->images[0]->source).".".fbpw_get_extension_from_url($image->images[0]->source);
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
	}

	return $feed;
}

function fbpw_get_html_from_feed(array $feed) {

	$output = "<div class='fbpw'>";

	foreach ($feed as $data) {
		$output .= "<a href='{$data['src']}' class='fbpw-image'>";
		$output .= "<img src='{$data['thumb']}'>";
		$output .= "</a>";
	}

	$output .= "</div> <div style='clear:both;'></div>";

	return $output;
}

function fbpw_get_extension_from_url($url) {
    // Split URL to get files extension
    $data = explode('.', strtok($url, '?'));
    return end($data);
}

function fbpw_error_message($message) {
    if (defined('WP_DEBUG') and WP_DEBUG) {
        return $message;
    }
}
