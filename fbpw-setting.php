<?php

//Read FB Pinwall Options
$options = get_option( 'fb-pinwall-options' );

define('FBPW_APP_ID', $options['facebook_app_key']);
define('FBPW_APP_SECRET', $options['facebook_app_secret']);
define('FBPW_FB_PAGES', $options['facebook_page']);
define('FBPW_NUM_POST', $options['num_posts']);
