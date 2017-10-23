<?php
defined('ABSPATH') or die("No script kiddies please!");

/**
 * Creates a thumnail from an image and mentains the aspect ratio
 *
 * @param $src Image source URL
 * @param $dest Thumbnail destination path
 * @param $desired_width Thumbnail width
 *
 * @return null
 */
function fbpw_make_thumb($src, $dest, $desired_width) {
    /* read the source image */
    $source_image = imagecreatefromstring(file_get_contents($src));
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}

/**
 * Gets the extension of an image provided by an URL
 *
 * @param string $url URL to image
 *
 * @return string Image extension
 */
function fbpw_get_extension_from_url($url) {
    // Split URL to get files extension
    $data = explode('.', strtok($url, '?'));
    return end($data);
}

/**
 * Returns message if WP_DEBUG is enabled
 *
 * @param string $message
 *
 * @return string
 */
function fbpw_error_message($message) {
    if (defined('WP_DEBUG') and WP_DEBUG) {
        return $message;
    }
}

/**
 * Function to load custom CSS into wordpress
 */
function fbpw_load_style()
{
    // Register style
    wp_register_style( 'colorbox', plugins_url( '/css/colorbox.css', __FILE__ ), array(), '20140131', 'all' );
    wp_enqueue_style( 'colorbox' );

    wp_register_style( 'fbpw', plugins_url( '/css/fbpw.css', __FILE__ ), array(), '20140131', 'all' );
    wp_enqueue_style( 'fbpw' );
}
// Load Plugin CSS
add_action( 'wp_enqueue_scripts', 'fbpw_load_style' );

/**
 * Function to load custom JS into wordpress
 */
function fbpw_load_script()
{
    wp_enqueue_script( 'masonry');

    // Register script
    wp_register_script( 'colorbox', plugins_url( '/js/jquery.colorbox-min.js', __FILE__ ), array(), '20140131', true);
    wp_enqueue_script( 'colorbox' );

    // Register script
    wp_register_script( 'fbpw', plugins_url( '/js/fbpw.js', __FILE__ ), array(), '20140131', true);
    wp_enqueue_script( 'fbpw' );
}
// Load Plugin JS
add_action( 'wp_enqueue_scripts', 'fbpw_load_script' );
