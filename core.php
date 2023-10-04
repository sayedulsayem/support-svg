<?php

namespace SupportSvg;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * main plugin loaded final class
 *
 * @author sayedulsayem 
 * @since 1.0.0
 */
final class Core {

    /**
     * accesing for object of this class
     *
     * @var object
     */
    private static $instance;

    /**
     * construct function of this class
     *
     * @return void
     * @since 1.0.0
     */
    public function __construct() {
        $this->define_constant();
    }

    /**
     * defining constant function
     *
     * @return void
     * @since 1.0.0
     */
    public function define_constant() {
        define( 'SUPPORT_SVG_VERSION', '1.0.0' );
        // define( 'SUPPORT_SVG_PACKAGE', 'free' );
        // define( 'SUPPORT_SVG_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
        // define( 'SUPPORT_SVG_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    }

    /**
     * plugin initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
        add_filter( 'wp_check_filetype_and_ext', [ $this, 'sanitize_svg_uploads' ], 10, 4 );

        add_filter( 'upload_mimes', [ $this, 'svg_modify_mimes' ] );

        add_action( 'admin_head', [ $this, 'svg_thumbnail_support' ] );
    }

    public function svg_modify_mimes( $existing_mimes ) {
        $existing_mimes['svg'] = 'image/svg+xml';
        return $existing_mimes;
    }

    public function svg_thumbnail_support() {
        ?>
        <style>
            .media-modal-content ul.attachments li.attachment img[src$=".svg"],
            .media-frame ul.attachments li.attachment img[src$=".svg"],
            table.media img[src$=".svg"] { 
                width: 100% !important; height: auto !important;
            }
        </style>
        <?php
    }

    public function sanitize_svg_uploads( $data, $file, $filename, $mimes ) {
        global $wp_version;
        if ( $wp_version !== '4.7.1' ) {
            return $data;
        }

        $filetype = wp_check_filetype( $filename, $mimes );

        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }

    public function flush_rewrites() {
        flush_rewrite_rules();
    }

    /**
     * singleton instance create function
     *
     * @return object
     * @since 1.0.0
     */
    public static function instance() {
        if ( !self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
