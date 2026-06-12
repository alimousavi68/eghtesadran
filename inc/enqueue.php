<?php
/**
 * Theme asset loading.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueues core theme assets needed for bootstrap.
 *
 * @return void
 */
function eghtesadran_enqueue_assets() {
	$theme_version        = eghtesadran_get_theme_version();
	$base_style_handle    = 'eghtesadran-base';
	$build_style_relative = 'assets/build/theme.css';

	wp_enqueue_style( 'eghtesadran-theme', get_stylesheet_uri(), array(), $theme_version );

	if ( eghtesadran_asset_exists( $build_style_relative ) ) {
		wp_enqueue_style(
			'eghtesadran-build',
			eghtesadran_asset_uri( $build_style_relative ),
			array( 'eghtesadran-theme' ),
			eghtesadran_get_asset_version( $build_style_relative )
		);
	}

	wp_enqueue_style(
		$base_style_handle,
		eghtesadran_asset_uri( 'assets/css/style.css' ),
		eghtesadran_asset_exists( $build_style_relative ) ? array( 'eghtesadran-build' ) : array( 'eghtesadran-theme' ),
		eghtesadran_get_asset_version( 'assets/css/style.css' )
	);

	if ( ! eghtesadran_asset_exists( $build_style_relative ) ) {
		wp_enqueue_script(
			'eghtesadran-tailwind-runtime',
			eghtesadran_asset_uri( 'assets/js/tailwind.js' ),
			array(),
			eghtesadran_get_asset_version( 'assets/js/tailwind.js' ),
			false
		);

		wp_add_inline_script(
			'eghtesadran-tailwind-runtime',
			"tailwind.config = {
				darkMode: 'class',
				theme: {
					extend: {
						colors: {
							primary: '#dc2626',
							'primary-hover': '#b91c1c',
							'background-light': '#f8f9fb',
							'text-main': '#0f172a',
							'text-sub': '#64748b',
							'border-main': '#e2e8f0'
						},
						fontFamily: {
							sans: ['IRANYekanX', 'Vazirmatn', 'system-ui', '-apple-system', 'sans-serif']
						},
						gridTemplateColumns: {
							'13': 'repeat(13, minmax(0, 1fr))'
						}
					}
				}
			};",
			'after'
		);
	}

	wp_enqueue_script(
		'eghtesadran-lucide',
		eghtesadran_asset_uri( 'assets/js/lucide.min.js' ),
		array(),
		eghtesadran_get_asset_version( 'assets/js/lucide.min.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_enqueue_script(
		'eghtesadran-main',
		eghtesadran_asset_uri( 'assets/js/main.js' ),
		array( 'eghtesadran-lucide' ),
		eghtesadran_get_asset_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'eghtesadran_enqueue_assets' );
