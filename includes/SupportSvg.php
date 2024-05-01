<?php

namespace SayedulSayem\SupportSvg;

use enshrined\svgSanitize\Sanitizer;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

class SupportSvg {
	use \Sayedulsayem\SupportSvg\Traits\Singleton;

	private $sanitizer;

	public function __construct() {
		$this->sanitizer = new Sanitizer();
	}

	public function init() {
		$this->define_constants();

		register_activation_hook( \plugin_dir_path( __DIR__ ) . 'support-svg.php', [ $this, 'activate' ] );

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	/**
	 * defining constant function
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function define_constants() {
		define( 'SUPPORT_SVG_VERSION', '1.1.0' );
	}

	public function activate() {
		flush_rewrite_rules();
	}

	public function init_plugin() {
		add_filter( 'upload_mimes', [ $this, 'svg_modify_mimes' ] );
		add_filter( 'wp_handle_upload_prefilter', [ $this, 'check_for_svg' ] );
		add_filter( 'wp_check_filetype_and_ext', [ $this, 'fix_mime_type_svg' ], 10, 4 );

		add_action( 'admin_head', [ $this, 'svg_thumbnail_support' ] );
	}

	public function svg_modify_mimes( $existing_mimes ) {
		$existing_mimes['svg'] = 'image/svg+xml';
		return $existing_mimes;
	}

	public function check_for_svg( $file ) {
		if ( ! isset( $file['tmp_name'] ) ) {
			return $file;
		}

		$file_name   = isset( $file['name'] ) ? $file['name'] : '';
		$wp_filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file_name );
		$type        = ! empty( $wp_filetype['type'] ) ? $wp_filetype['type'] : '';

		if ( 'image/svg+xml' === $type ) {
			if ( ! $this->sanitize( $file['tmp_name'] ) ) {
				$file['error'] = __(
					"Sorry, this file couldn't be sanitized so for security reasons wasn't uploaded",
					'support-svg'
				);
			}
		}

		return $file;
	}

	public function sanitize( $file ) {
		$dirty_svg = file_get_contents( $file ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$this->sanitizer->minify( true );
		$clean_svg = $this->sanitizer->sanitize( $dirty_svg );
		if ( false === $clean_svg ) {
			return false;
		}
		file_put_contents( $file, $clean_svg ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

		return true;
	}

	public function fix_mime_type_svg( $data = null, $file = null, $filename = null, $mimes = null ) {
		$ext = isset( $data['ext'] ) ? $data['ext'] : '';
		if ( strlen( $ext ) < 1 ) {
			$exploded = explode( '.', $filename );
			$ext      = strtolower( end( $exploded ) );
		}
		if ( 'svg' === $ext ) {
			$data['type'] = 'image/svg+xml';
			$data['ext']  = 'svg';
		}

		return $data;
	}

	public function svg_thumbnail_support() {
		?>
		<style>
			.media-modal-content ul.attachments li.attachment img[src$=".svg"],
			.media-frame ul.attachments li.attachment img[src$=".svg"],
			table.media img[src$=".svg"] {
				width: 100% !important;
				height: auto !important;
			}
		</style>
		<?php
	}
}
