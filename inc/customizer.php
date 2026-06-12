<?php
/**
 * Theme Customizer registrations.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers customizer options used by the global header and footer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function eghtesadran_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'eghtesadran_theme_options',
		array(
			'title'    => __( 'Theme Options', 'eghtesadran' ),
			'priority' => 160,
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_footer_brand_text',
		array(
			'default'           => eghtesadran_get_default_brand_text(),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_footer_brand_text',
		array(
			'label'   => __( 'Footer brand text', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_footer_copyright_text',
		array(
			'default'           => eghtesadran_get_default_copyright_text(),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_footer_copyright_text',
		array(
			'label'   => __( 'Copyright text', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_header_ticker_label',
		array(
			'default'           => __( 'اخبار فوری', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_header_ticker_label',
		array(
			'label'   => __( 'Header Ticker Label', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_footer_quick_links_title',
		array(
			'default'           => __( 'دسترسی سریع', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_footer_quick_links_title',
		array(
			'label'   => __( 'Footer Quick Links Title', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_footer_contact_title',
		array(
			'default'           => __( 'ارتباط با تحریریه', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_footer_contact_title',
		array(
			'label'   => __( 'Footer Contact Title', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_footer_licenses_title',
		array(
			'default'           => __( 'مجوزهای رسانه', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_footer_licenses_title',
		array(
			'label'   => __( 'Footer Licenses Title', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_contact_address',
		array(
			'default'           => 'تهران، خیابان ولیعصر، برج تجاری-اداری مالی ساعی، طبقه هفتم',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_contact_address',
		array(
			'label'   => __( 'Address', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_contact_phone',
		array(
			'default'           => '۰۲۱ - ۸۸۹۹ ۷۷۶۶',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_contact_phone',
		array(
			'label'   => __( 'Phone number', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_contact_email',
		array(
			'default'           => 'editorial@eghtesadran.com',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_contact_email',
		array(
			'label'   => __( 'Email address', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'email',
		)
	);

	foreach ( eghtesadran_get_social_platforms() as $key => $platform ) {
		$setting_id = 'eghtesadran_social_' . $key;

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'       => sprintf(
					/* translators: %s: social platform label. */
					__( '%s URL', 'eghtesadran' ),
					$platform['label']
				),
				'section'     => 'eghtesadran_theme_options',
				'type'        => 'url',
				'input_attrs' => array(
					'placeholder' => 'https://',
				),
			)
		);
	}

	// Multimedia Section
	$wp_customize->add_section(
		'eghtesadran_multimedia_options',
		array(
			'title'    => __( 'تنظیمات بخش چندرسانه‌ای', 'eghtesadran' ),
			'priority' => 170,
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_multimedia_title',
		array(
			'default'           => __( 'چندرسانه‌ای', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_multimedia_title',
		array(
			'label'   => __( 'عنوان بخش چندرسانه‌ای', 'eghtesadran' ),
			'section' => 'eghtesadran_multimedia_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_multimedia_desc',
		array(
			'default'           => __( 'گزارش‌های ویدئویی و تصویری اختصاصی', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_multimedia_desc',
		array(
			'label'   => __( 'توضیحات بخش چندرسانه‌ای', 'eghtesadran' ),
			'section' => 'eghtesadran_multimedia_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_multimedia_posts_per_page',
		array(
			'default'           => 3,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_multimedia_posts_per_page',
		array(
			'label'   => __( 'تعداد نمایش آیتم‌ها', 'eghtesadran' ),
			'section' => 'eghtesadran_multimedia_options',
			'type'    => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_multimedia_orderby',
		array(
			'default'           => 'date',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_multimedia_orderby',
		array(
			'label'   => __( 'مرتب‌سازی بر اساس', 'eghtesadran' ),
			'section' => 'eghtesadran_multimedia_options',
			'type'    => 'select',
			'choices' => array(
				'date'       => __( 'تاریخ انتشار', 'eghtesadran' ),
				'menu_order' => __( 'ترتیب دستی (Menu Order)', 'eghtesadran' ),
				'title'      => __( 'عنوان', 'eghtesadran' ),
				'rand'       => __( 'تصادفی', 'eghtesadran' ),
			),
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_multimedia_order',
		array(
			'default'           => 'DESC',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_multimedia_order',
		array(
			'label'   => __( 'جهت مرتب‌سازی', 'eghtesadran' ),
			'section' => 'eghtesadran_multimedia_options',
			'type'    => 'select',
			'choices' => array(
				'DESC' => __( 'نزولی (جدیدترین به قدیمی‌ترین / از بزرگ به کوچک)', 'eghtesadran' ),
				'ASC'  => __( 'صعودی (قدیمی‌ترین به جدیدترین / از کوچک به بزرگ)', 'eghtesadran' ),
			),
		)
	);

	// Add selective refresh support for the multimedia section
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'eghtesadran_multimedia_partial',
			array(
				'selector'            => '.eghtesadran-multimedia-section-wrapper',
				'render_callback'     => 'eghtesadran_customize_partial_multimedia',
				'container_inclusive' => true,
				'fallback_refresh'    => true,
			)
		);
	}
}
add_action( 'customize_register', 'eghtesadran_customize_register' );

/**
 * Render callback for the multimedia selective refresh partial.
 *
 * @return void
 */
function eghtesadran_customize_partial_multimedia() {
	get_template_part( 'template-parts/home/multimedia' );
}

