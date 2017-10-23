=== FB Pinwall ===
Contributors: nezhar
Tags: pinwall, facebook
Tested up to: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple Wordpress plugin for creating a pinwall of the newest images posted on one or more Facebook pages.

== Description ==

Usage:

    [fbpw]
or

    [fbpw fb-pages='pageId' num-posts='numPosts']

The plugin is using Facebooks 2.x API, which requires you to setup an App with a valid key and secret: https://developers.facebook.com/docs/apps/register

A settings page is available for configuring the plugin (Settings -> FB Pinwall)

Available options:

* Facebook App Key
* Facebook App Secret
* Facebook Page for Feed
* Number of Posts from Feed


If **fb-pages** or **num-posts** are set in the shortcode, the options will be overwritten.


The first call is creating a list of thumbnails that are stored locally (first call will usually take longer). The images are served from Facebook.

Images get enlarged using Colorbox: http://www.jacklmoore.com/colorbox/

#### Debug

Debug information is shoud if you enable the **WP_DEBUG** option inside the **wp-config.php** file of your Wordpress isntalation.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->FB Pinwall screen to configure the plugin

== Changelog ==

= 1.1.0 =
Fixes for facebook api v2.10

= 1.0.2 =
Refactoring shortcode and functions

= 1.0.1 =
Add debug information

= 1.0.0 =
Initial release
