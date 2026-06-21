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

	// Related Posts Settings (Single Post)
	$wp_customize->add_setting(
		'eghtesadran_related_posts_count',
		array(
			'default'           => 2,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_related_posts_count',
		array(
			'label'       => __( 'تعداد مطالب مرتبط (تک‌نوشته)', 'eghtesadran' ),
			'section'     => 'eghtesadran_theme_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 10,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'eghtesadran_related_posts_query_type',
		array(
			'default'           => 'category',
			'sanitize_callback' => 'sanitize_key',
		)
	);

	$wp_customize->add_control(
		'eghtesadran_related_posts_query_type',
		array(
			'label'   => __( 'نوع کویری مطالب مرتبط (تک‌نوشته)', 'eghtesadran' ),
			'section' => 'eghtesadran_theme_options',
			'type'    => 'select',
			'choices' => array(
				'category' => __( 'بر اساس دسته‌بندی', 'eghtesadran' ),
				'tag'      => __( 'بر اساس برچسب‌ها', 'eghtesadran' ),
				'both'     => __( 'بر اساس دسته‌بندی و برچسب‌ها', 'eghtesadran' ),
			),
		)
	);

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

	// Hero Section Settings
	$wp_customize->add_section(
		'eghtesadran_hero_options',
		array(
			'title'    => __( 'تنظیمات بخش هیرو', 'eghtesadran' ),
			'priority' => 165,
		)
	);

	$categories = get_categories( array( 'hide_empty' => false ) );
	$category_choices = array(
		'0' => __( 'همه دسته‌ها', 'eghtesadran' ),
	);
	foreach ( $categories as $cat ) {
		$category_choices[ $cat->term_id ] = $cat->name;
	}

	$lucide_icons = array(
		'trending-up'  => 'Trending Up (پربازدید)',
		'clock'        => 'Clock (آخرین اخبار)',
		'flame'        => 'Flame (داغ)',
		'zap'          => 'Zap (ویژه)',
		'newspaper'    => 'Newspaper (اخبار)',
		'star'         => 'Star (ستاره)',
		'heart'        => 'Heart (محبوب)',
		'eye'          => 'Eye (مشاهده)',
		'bar-chart-2'  => 'Chart (آمار)',
		'award'        => 'Award (برگزیده)',
		'bell'         => 'Bell (اطلاعیه)',
		'briefcase'    => 'Briefcase (اقتصادی)',
		'coins'        => 'Coins (ارز و طلا)',
		'shopping-bag' => 'Shopping Bag (بازار)',
		'home'         => 'Home (مسکن)',
		'car'          => 'Car (خودرو)',
	);

	// Display Hero
	$wp_customize->add_setting(
		'eghtesadran_hero_display',
		array(
			'default'           => '1',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_display',
		array(
			'label'    => __( 'نمایش بخش هیرو؟', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'checkbox',
		)
	);

	// Right side Category
	$wp_customize->add_setting(
		'eghtesadran_hero_right_cat',
		array(
			'default'           => '0',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_right_cat',
		array(
			'label'    => __( 'دسته‌بندی بخش راست (اصلی)', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'select',
			'choices'  => $category_choices,
		)
	);

	// Left side Category
	$wp_customize->add_setting(
		'eghtesadran_hero_left_cat',
		array(
			'default'           => '0',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_left_cat',
		array(
			'label'    => __( 'دسته‌بندی بخش چپ (لیست)', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'select',
			'choices'  => $category_choices,
		)
	);

	// Right side Offset
	$wp_customize->add_setting(
		'eghtesadran_hero_right_offset',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_right_offset',
		array(
			'label'    => __( 'افست بخش راست (پرش از چند مورد اول)', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'number',
		)
	);

	// Left side Offset
	$wp_customize->add_setting(
		'eghtesadran_hero_left_offset',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_left_offset',
		array(
			'label'    => __( 'افست بخش چپ (پرش از چند مورد اول)', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'number',
		)
	);

	// Left side count
	$wp_customize->add_setting(
		'eghtesadran_hero_left_count',
		array(
			'default'           => 5,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_left_count',
		array(
			'label'    => __( 'تعداد اخبار بخش چپ', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			),
		)
	);

	// Show/Hide Date
	$wp_customize->add_setting(
		'eghtesadran_hero_show_date',
		array(
			'default'           => '1',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_show_date',
		array(
			'label'    => __( 'نمایش تاریخ؟', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'checkbox',
		)
	);

	// Show/Hide Category tag
	$wp_customize->add_setting(
		'eghtesadran_hero_show_cat_tag',
		array(
			'default'           => '1',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_show_cat_tag',
		array(
			'label'    => __( 'نمایش تگ دسته‌بندی؟', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'checkbox',
		)
	);

	// Left side Title
	$wp_customize->add_setting(
		'eghtesadran_hero_left_title',
		array(
			'default'           => __( 'مهم ترین اخبار', 'eghtesadran' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_left_title',
		array(
			'label'    => __( 'عنوان بخش چپ', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'text',
		)
	);

	// Left side Icon
	$wp_customize->add_setting(
		'eghtesadran_hero_left_icon',
		array(
			'default'           => 'trending-up',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'eghtesadran_hero_left_icon',
		array(
			'label'    => __( 'آیکن بخش چپ', 'eghtesadran' ),
			'section'  => 'eghtesadran_hero_options',
			'type'     => 'select',
			'choices'  => $lucide_icons,
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

