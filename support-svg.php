<?php

/**
 * Support SVG
 *
 * @package           support-svg
 * @author            Sayedul Sayem
 * @copyright         2023 - 2024 Sayedulsayem
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Support SVG
 * Plugin URI:        https://wordpress.org/plugins/support-svg/
 * Description:       Lightest plugin to upload svg in WordPress
 * Version:           1.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            Sayedul Sayem
 * Author URI:        https://sayedulsayem.com
 * Text Domain:       support-svg
 * Domain Path:       /i18n/
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Sayedulsayem\SupportSvg;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;


if ( ! class_exists( SupportSvg::class ) && is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	/** @noinspection PhpIncludeInspection */
	require_once __DIR__ . '/vendor/autoload.php';
}

class_exists( SupportSvg::class ) && SupportSvg::instance()->init();
