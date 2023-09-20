<?php

/**
 * Support SVG
 *
 * @package           support-svg
 * @author            Sayedul Sayem
 * @copyright         2023 Sayedulsayem
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Support SVG
 * Plugin URI:        https://wordpress.org/plugins/support-svg/
 * Description:       Lightest plugin to upload svg in WordPress
 * Version:           1.0.0
 * Requires at least: 4.7
 * Requires PHP:      5.6
 * Author:            sayedulsayem
 * Author URI:        https://sayedulsayem.com
 * Text Domain:       support-svg
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// run plugin initialization file
require 'plugin.php';

/**
 * update permalink after register cpt
 */
register_activation_hook( __FILE__, [ SupportSvg\Plugin::instance(), 'flush_rewrites' ] );

/**
 * load plugin after initialize wordpress core
 */
add_action( 'plugins_loaded', function () {
    SupportSvg\Plugin::instance()->init();
} );
