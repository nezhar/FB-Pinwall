<?php

/*  Copyright 2015  Nezbeda Harald  (email : dev@nezhar.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Plugin Name: Facebook Pinwall
 * Plugin URI: http://nezhar.com/
 * Description: A simple way to add a facebook image feed as a pinwall to wordpress
 * Version: 0.0.1
 * Author: Nezbeda Harald
 * Author URI: http://nezhar.com/
 * Text Domain: fbpw
 * Domain Path: /lang/
 * License: GPL2
 */

defined('ABSPATH') or die("No script kiddies please!");

include("fbpw-setting.php");
include("fbpw-headers.php");
include("fbpw-options.php");
include("fbpw-shortcode.php");