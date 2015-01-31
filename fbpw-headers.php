<?php

function load_style()
{
    // Register style
    wp_register_style( 'fbpw', plugins_url( '/css/fbpw.css', __FILE__ ), array(), '20140131', 'all' );
    wp_enqueue_style( 'fbpw' );
}

function load_script()
{
	// Register script
    wp_register_script( 'masonry', plugins_url( '/js/masonry.pkgd.min.js', __FILE__ ), array(), '20140131', true);
    wp_enqueue_script( 'masonry');

    // Register script
    wp_register_script( 'fbpw', plugins_url( '/js/fbpw.js', __FILE__ ), array(), '20140131', true);
    wp_enqueue_script( 'fbpw' );
}

add_action( 'wp_enqueue_scripts', 'load_script' );
add_action( 'wp_enqueue_scripts', 'load_style' );