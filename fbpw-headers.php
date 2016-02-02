<?php

function load_style()
{
    // Register style
    wp_register_style( 'colorbox', plugins_url( '/css/colorbox.css', __FILE__ ), array(), '20140131', 'all' );
    wp_enqueue_style( 'colorbox' );

    wp_register_style( 'fbpw', plugins_url( '/css/fbpw.css', __FILE__ ), array(), '20140131', 'all' );
    wp_enqueue_style( 'fbpw' );
}

function load_script()
{
    wp_enqueue_script( 'masonry');

    // Register script
    wp_register_script( 'colorbox', plugins_url( '/js/jquery.colorbox-min.js', __FILE__ ), array(), '20140131', true);
    wp_enqueue_script( 'colorbox' );

    // Register script
    wp_register_script( 'fbpw', plugins_url( '/js/fbpw.js', __FILE__ ), array(), '20140131', true);
    wp_enqueue_script( 'fbpw' );
}

add_action( 'wp_enqueue_scripts', 'load_script' );
add_action( 'wp_enqueue_scripts', 'load_style' );
