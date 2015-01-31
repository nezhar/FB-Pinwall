<?php

//Read FB Pinwall Options
$options = get_option( 'fb-pinwall-options' );

define('APP_ID', $options['facebook_app_key']);
define('APP_SECRET', $options['facebook_app_secret']);
define('FB_PAGES', $options['facebook_page']);