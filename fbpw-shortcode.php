<?php
defined('ABSPATH') or die("No script kiddies please!");

add_shortcode( 'fbpw', 'fbpw_func' );

/**
 * Function for [fbpw] shortocde
 */
function fbpw_func($atts) {
    $fbpw_options = get_option( 'fb-pinwall-options' );

    $options = shortcode_atts(array(
        'fb-pages' => $fbpw_options['facebook_page'],
        'num-posts' => $fbpw_options['num_posts']
    ), $atts);

    if ($fbpw_options['facebook_app_key'] && $fbpw_options['facebook_app_secret']) {
        try {
            //Get Data from Facebook feeds
        	$feed = new fbpw\FbFeed(
                $fbpw_options['facebook_app_key'],
                $fbpw_options['facebook_app_secret'],
                $options['fb-pages'],
                $options['num-posts']
            );

        	//Create HTML of the feed Data
        	$html = $feed->getHtml();

            //Replace Shortcode
        	return $html;
        } catch (Exception $e) {
            return fbpw_error_message($e->getMessage());
        }
    } else {
        return fbpw_error_message('APP_ID and APP_SECRET are not configured');
    }
}
