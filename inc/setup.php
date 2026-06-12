<?php
/**
 * Theme setup hooks.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers theme supports and menus.
 *
 * @return void
 */
function eghtesadran_theme_setup() {
	load_theme_textdomain( 'eghtesadran', get_theme_file_path( '/languages' ) );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_image_size( 'eghtesadran-hero', 960, 540, true );
	add_image_size( 'eghtesadran-card-lg', 640, 360, true );
	add_image_size( 'eghtesadran-card-md', 480, 270, true );
	add_image_size( 'eghtesadran-thumb', 320, 180, true );
	add_image_size( 'eghtesadran-related', 240, 240, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'eghtesadran' ),
			'mobile'  => __( 'Mobile Menu', 'eghtesadran' ),
			'footer'  => __( 'Footer Menu', 'eghtesadran' ),
		)
	);
}
add_action( 'after_setup_theme', 'eghtesadran_theme_setup' );

/**
 * Registers widget areas.
 *
 * @return void
 */
function eghtesadran_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Home Sidebar', 'eghtesadran' ),
			'id'            => 'sidebar-home',
			'description'   => __( 'Add widgets here to appear in your homepage sidebar.', 'eghtesadran' ),
			'before_widget' => '<section id="%1$s" class="widget eghtesadran-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Single Sidebar', 'eghtesadran' ),
			'id'            => 'sidebar-single',
			'description'   => __( 'Add widgets here to appear in your single post sidebar.', 'eghtesadran' ),
			'before_widget' => '<section id="%1$s" class="widget eghtesadran-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Ad Rail Sidebar', 'eghtesadran' ),
			'id'            => 'sidebar-ad-rail',
			'description'   => __( 'Add advertising widgets here.', 'eghtesadran' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="sr-only">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Header Ad Slot (600x60)', 'eghtesadran' ),
			'id'            => 'sidebar-header-ad',
			'description'   => __( 'Add a 600x60 ad widget here for the header.', 'eghtesadran' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s w-full flex justify-center">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="sr-only">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'صفحه اصلی - بخش مطالب', 'eghtesadran' ),
			'id'            => 'home-content',
			'description'   => __( 'ویجت‌های چند منظوره موضوعی صفحه اصلی را در این بخش قرار دهید.', 'eghtesadran' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="sr-only">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'eghtesadran_widgets_init' );
