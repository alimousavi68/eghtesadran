<?php
/**
 * Helper functions for the Eghtesadran theme.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the current theme version.
 *
 * @return string
 */
function eghtesadran_get_theme_version() {
	$theme = wp_get_theme();

	return $theme->get( 'Version' ) ?: '0.1.0';
}

/**
 * Builds a theme asset path from a relative path.
 *
 * @param string $relative_path Asset path relative to the theme root.
 * @return string
 */
function eghtesadran_asset_path( $relative_path ) {
	$relative_path = ltrim( $relative_path, '/' );

	return get_theme_file_path( '/' . $relative_path );
}

/**
 * Builds a theme asset URI from a relative path.
 *
 * @param string $relative_path Asset path relative to the theme root.
 * @return string
 */
function eghtesadran_asset_uri( $relative_path ) {
	$relative_path = ltrim( $relative_path, '/' );

	return get_theme_file_uri( '/' . $relative_path );
}

/**
 * Returns a version string for a theme asset based on filemtime when possible.
 *
 * @param string $relative_path Asset path relative to the theme root.
 * @return string
 */
function eghtesadran_get_asset_version( $relative_path ) {
	$asset_path = eghtesadran_asset_path( $relative_path );

	if ( file_exists( $asset_path ) ) {
		return (string) filemtime( $asset_path );
	}

	return eghtesadran_get_theme_version();
}

/**
 * Checks whether a theme asset exists on disk.
 *
 * @param string $relative_path Asset path relative to the theme root.
 * @return bool
 */
function eghtesadran_asset_exists( $relative_path ) {
	return file_exists( eghtesadran_asset_path( $relative_path ) );
}

/**
 * Returns the default footer brand text.
 *
 * @return string
 */
function eghtesadran_get_default_brand_text() {
	return 'پورتال تحلیلی خبری اقتصاد ران؛ مرجع تخصصی ارائه دقیق‌ترین و به‌روزترین گزارش‌ها، آمار، روندهای تحلیلی و خبرهای موثر بر بازارهای مالی، صنعتی، بورس، طلا و خودرو در کشور.';
}

/**
 * Returns the default copyright text.
 *
 * @return string
 */
function eghtesadran_get_default_copyright_text() {
	return 'کلیه حقوق مادی و معنوی، متعلق به پورتال خبری تحلیلی اقتصاد ران می‌باشد.';
}

/**
 * Returns the social platforms supported by the theme.
 *
 * @return array<string, array<string, string>>
 */
function eghtesadran_get_social_platforms() {
	return array(
		'telegram'  => array(
			'label' => __( 'Telegram', 'eghtesadran' ),
			'icon'  => 'send',
		),
		'instagram' => array(
			'label' => __( 'Instagram', 'eghtesadran' ),
			'icon'  => 'instagram',
		),
		'x'         => array(
			'label' => __( 'X', 'eghtesadran' ),
			'icon'  => 'twitter',
		),
		'linkedin'  => array(
			'label' => __( 'LinkedIn', 'eghtesadran' ),
			'icon'  => 'linkedin',
		),
	);
}

/**
 * Returns configured social links that have usable URLs.
 *
 * @return array<int, array<string, string>>
 */
function eghtesadran_get_social_links() {
	$social_links = array();

	foreach ( eghtesadran_get_social_platforms() as $key => $platform ) {
		$url = trim( (string) get_theme_mod( 'eghtesadran_social_' . $key, '' ) );

		if ( '' === $url ) {
			continue;
		}

		$social_links[] = array(
			'key'   => $key,
			'label' => $platform['label'],
			'icon'  => $platform['icon'],
			'url'   => $url,
		);
	}

	return $social_links;
}

/**
 * Returns the configured contact information.
 *
 * @return array<string, string>
 */
function eghtesadran_get_contact_details() {
	return array(
		'address' => trim( (string) get_theme_mod( 'eghtesadran_contact_address', 'تهران، خیابان ولیعصر، برج تجاری-اداری مالی ساعی، طبقه هفتم' ) ),
		'phone'   => trim( (string) get_theme_mod( 'eghtesadran_contact_phone', '۰۲۱ - ۸۸۹۹ ۷۷۶۶' ) ),
		'email'   => trim( (string) get_theme_mod( 'eghtesadran_contact_email', 'editorial@eghtesadran.com' ) ),
	);
}

/**
 * Returns the configured brand text.
 *
 * @return string
 */
function eghtesadran_get_brand_text() {
	return trim( (string) get_theme_mod( 'eghtesadran_footer_brand_text', eghtesadran_get_default_brand_text() ) );
}

/**
 * Returns the configured copyright text.
 *
 * @return string
 */
function eghtesadran_get_copyright_text() {
	return trim( (string) get_theme_mod( 'eghtesadran_footer_copyright_text', eghtesadran_get_default_copyright_text() ) );
}

/**
 * Returns the site logo markup with a default asset fallback.
 *
 * @param string $image_class CSS classes for the image element.
 * @return string
 */
function eghtesadran_get_site_logo( $image_class = '' ) {
	$custom_logo_id = (int) get_theme_mod( 'custom_logo' );

	if ( $custom_logo_id ) {
		return wp_get_attachment_image(
			$custom_logo_id,
			'full',
			false,
			array(
				'class'   => trim( $image_class ),
				'alt'     => get_bloginfo( 'name' ),
				'loading' => false,
			)
		);
	}

	if ( eghtesadran_asset_exists( 'assets/images/logo.webp' ) ) {
		return sprintf(
			'<img src="%1$s" alt="%2$s" class="%3$s" loading="eager" />',
			esc_url( eghtesadran_asset_uri( 'assets/images/logo.webp' ) ),
			esc_attr( get_bloginfo( 'name' ) ),
			esc_attr( trim( $image_class ) )
		);
	}

	return sprintf(
		'<span class="%1$s">%2$s</span>',
		esc_attr( trim( $image_class ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Renders the primary menu fallback.
 *
 * @return void
 */
function eghtesadran_primary_menu_fallback() {
	$items = array(
		array(
			'label' => __( 'صفحه اصلی', 'eghtesadran' ),
			'url'   => home_url( '/' ),
		),
	);

	foreach ( get_pages( array( 'sort_column' => 'menu_order,post_title' ) ) as $page ) {
		$items[] = array(
			'label' => $page->post_title,
			'url'   => get_permalink( $page ),
		);
	}

	echo '<ul class="flex w-full text-[13px] lg:text-sm font-bold items-center h-full list-none">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $items as $item ) {
		printf(
			'<li class="h-full flex items-center nav-divider"><a href="%1$s" class="h-full flex items-center px-3 lg:px-5 hover:bg-white/10 transition-colors">%2$s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}

	echo '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Renders the mobile menu fallback.
 *
 * @return void
 */
function eghtesadran_mobile_menu_fallback() {
	$items = array(
		array(
			'label' => __( 'صفحه اصلی', 'eghtesadran' ),
			'url'   => home_url( '/' ),
		),
	);

	foreach ( get_pages( array( 'sort_column' => 'menu_order,post_title' ) ) as $page ) {
		$items[] = array(
			'label' => $page->post_title,
			'url'   => get_permalink( $page ),
		);
	}

	echo '<ul class="flex flex-col gap-1 list-none">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $items as $index => $item ) {
		$item_classes = 'py-2.5 px-4 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary text-slate-800 dark:text-slate-200 font-medium transition-colors block';

		if ( 0 === $index ) {
			$item_classes = 'py-2.5 px-4 rounded-lg bg-red-50 text-primary dark:bg-red-950/30 dark:text-red-400 font-bold transition-colors block';
		}

		printf(
			'<li><a href="%1$s" class="%2$s">%3$s</a></li>',
			esc_url( $item['url'] ),
			esc_attr( $item_classes ),
			esc_html( $item['label'] )
		);
	}

	echo '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Renders the footer menu fallback.
 *
 * @return void
 */
function eghtesadran_footer_menu_fallback() {
	$items = array();

	foreach ( get_pages( array( 'sort_column' => 'menu_order,post_title' ) ) as $page ) {
		$items[] = array(
			'label' => $page->post_title,
			'url'   => get_permalink( $page ),
		);
	}

	echo '<ul class="footer-accordion-content hidden sm:block space-y-2.5 font-bold text-xs text-slate-400 pt-2 list-none">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $items as $item ) {
		printf(
			'<li><a href="%1$s" class="hover:text-white flex items-center gap-1.5"><i data-lucide="chevron-left" class="w-3.5 h-3.5 text-slate-650 shrink-0"></i><span>%2$s</span></a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}

	echo '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Returns a flat list of top-level items for a menu location.
 *
 * @param string $location Menu location slug.
 * @return array<int, array<string, mixed>>
 */
function eghtesadran_get_flat_menu_items( $location ) {
	$items          = array();
	$menu_locations = get_nav_menu_locations();

	if ( isset( $menu_locations[ $location ] ) ) {
		$menu_items = wp_get_nav_menu_items( $menu_locations[ $location ] );

		if ( is_array( $menu_items ) ) {
			foreach ( $menu_items as $menu_item ) {
				if ( ! empty( $menu_item->menu_item_parent ) ) {
					continue;
				}

				$classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;

				$items[] = array(
					'label'   => $menu_item->title,
					'url'     => $menu_item->url,
					'current' => in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true ),
				);
			}
		}
	}

	if ( ! empty( $items ) ) {
		return $items;
	}

	$items[] = array(
		'label'   => __( 'صفحه اصلی', 'eghtesadran' ),
		'url'     => home_url( '/' ),
		'current' => is_front_page() || is_home(),
	);

	foreach ( get_pages( array( 'sort_column' => 'menu_order,post_title' ) ) as $page ) {
		$items[] = array(
			'label'   => $page->post_title,
			'url'     => get_permalink( $page ),
			'current' => is_page( $page->ID ),
		);
	}

	return $items;
}

/**
 * Returns recent posts for the header ticker.
 *
 * @param int $limit Number of items to return.
 * @return array<int, array<string, string>>
 */
function eghtesadran_get_ticker_items( $limit = 5 ) {
	$limit = max( 1, (int) $limit );
	$items = array();

	$posts = get_posts(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $limit,
			'ignore_sticky_posts' => false,
			'orderby'             => 'date',
			'order'               => 'DESC',
		)
	);

	foreach ( $posts as $post ) {
		$items[] = array(
			'label' => get_the_title( $post ),
			'url'   => get_permalink( $post ),
		);
	}

	if ( ! empty( $items ) ) {
		return $items;
	}

	foreach ( get_pages( array( 'sort_column' => 'menu_order,post_title', 'number' => $limit ) ) as $page ) {
		$items[] = array(
			'label' => $page->post_title,
			'url'   => get_permalink( $page ),
		);
	}

	if ( ! empty( $items ) ) {
		return $items;
	}

	return array(
		array(
			'label' => __( 'آخرین به‌روزرسانی‌های سایت به‌زودی از این بخش نمایش داده می‌شوند.', 'eghtesadran' ),
			'url'   => home_url( '/' ),
		),
	);
}

/**
 * Helper to render a category section based on a specific layout.
 *
 * @param string $category_slug Category slug.
 * @param string $layout        Layout type (A, B, C).
 * @return void
 */
function eghtesadran_render_category_section( $category_slug, $layout = 'A' ) {
	$category = get_category_by_slug( $category_slug );
	if ( ! $category ) {
		return;
	}

	$posts_per_page = ( 'C' === $layout ) ? 4 : 3;

	$query = new WP_Query(
		array(
			'category_name'  => $category_slug,
			'posts_per_page' => $posts_per_page,
		)
	);

	if ( ! $query->have_posts() ) {
		return;
	}

	$category_link = get_category_link( $category->term_id );
	?>
	<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
		<div class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-slate-700 pb-3">
			<div class="flex items-center gap-3">
				<div class="w-1.5 h-6 bg-primary rounded-full"></div>
				<h2 class="text-xl font-extrabold text-slate-900 dark:text-white"><?php echo esc_html( $category->name ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $category_link ); ?>" class="group flex text-[11px] font-bold text-slate-400 dark:text-slate-500 hover:text-primary transition-all duration-300 gap-1 items-center px-2.5 py-1 rounded-full hover:bg-slate-50 dark:hover:bg-slate-700 border border-transparent hover:border-slate-200">
				<?php esc_html_e( 'مشاهده همه', 'eghtesadran' ); ?>
				<i data-lucide="chevron-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" aria-hidden="true"></i>
			</a>
		</div>

		<?php if ( 'A' === $layout ) : ?>
			<div class="grid grid-cols-1 gap-5">
				<?php $count = 0; while ( $query->have_posts() ) : $query->the_post(); $count++; ?>
					<?php if ( 1 === $count ) : ?>
						<div class="group cursor-pointer">
							<a href="<?php the_permalink(); ?>" class="block">
								<div class="relative rounded-xl overflow-hidden aspect-[16/9] mb-4 shadow-sm">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'eghtesadran-card-md', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-103' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
									<?php endif; ?>
								</div>
								<div class="flex items-center gap-2 mb-2">
									<span class="flex items-center gap-1 text-slate-400/90 dark:text-slate-500/90 text-[10px] font-medium">
										<i data-lucide="clock" class="w-3 h-3 opacity-70" aria-hidden="true"></i>
										<?php echo esc_html( get_the_date() ); ?>
									</span>
								</div>
								<h3 class="text-base md:text-lg font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors mb-2">
									<?php the_title(); ?>
								</h3>
								<p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm leading-relaxed text-justify mb-4">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?>
								</p>
							</a>
						</div>
						<div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700">
					<?php else : ?>
						<a href="<?php the_permalink(); ?>" class="group flex items-start gap-2 text-xs md:text-sm font-medium text-slate-700 dark:text-slate-350 hover:text-primary dark:hover:text-red-400 transition-colors">
							<span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2"></span>
							<span><?php the_title(); ?></span>
						</a>
					<?php endif; ?>
				<?php endwhile; ?>
				</div>
			</div>

		<?php elseif ( 'B' === $layout ) : ?>
			<div class="flex flex-col gap-5">
				<?php $count = 0; while ( $query->have_posts() ) : $query->the_post(); $count++; ?>
					<?php if ( 1 === $count ) : ?>
						<div class="group cursor-pointer flex flex-col sm:flex-row gap-4">
							<a href="<?php the_permalink(); ?>" class="flex flex-col sm:flex-row gap-4 w-full">
								<div class="w-full sm:w-44 md:w-48 relative rounded-xl overflow-hidden aspect-[4/3] sm:aspect-auto shrink-0 shadow-sm">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'eghtesadran-thumb', array( 'class' => 'w-full h-full object-cover transition-transform duration-750 group-hover:scale-105' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
									<?php endif; ?>
								</div>
								<div class="flex flex-col justify-center flex-1">
									<div class="flex items-center gap-2 mb-2">
										<span class="flex items-center gap-1 text-slate-400/90 dark:text-slate-500/90 text-[10px] font-medium">
											<i data-lucide="clock" class="w-3 h-3 opacity-70" aria-hidden="true"></i>
											<?php echo esc_html( get_the_date() ); ?>
										</span>
									</div>
									<h3 class="text-base font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors mb-2 leading-snug">
										<?php the_title(); ?>
									</h3>
									<p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm leading-relaxed text-justify">
										<?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?>
									</p>
								</div>
							</a>
						</div>
						<div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700">
					<?php else : ?>
						<a href="<?php the_permalink(); ?>" class="group flex items-start gap-2 text-xs md:text-sm font-medium text-slate-700 dark:text-slate-350 hover:text-primary dark:hover:text-red-400 transition-colors">
							<span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2"></span>
							<span><?php the_title(); ?></span>
						</a>
					<?php endif; ?>
				<?php endwhile; ?>
				</div>
			</div>

		<?php elseif ( 'C' === $layout ) : ?>
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<div class="group cursor-pointer bg-slate-50 dark:bg-slate-900/40 p-3 rounded-xl border border-slate-100 dark:border-slate-700 hover:shadow-sm transition-all">
						<a href="<?php the_permalink(); ?>" class="block">
							<div class="relative rounded-lg overflow-hidden aspect-[16/10] mb-3">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'eghtesadran-thumb', array( 'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
								<?php endif; ?>
							</div>
							<div class="flex items-center justify-end mb-2">
								<span class="flex items-center gap-1 text-slate-400/90 dark:text-slate-500/90 text-[10px] font-medium">
									<i data-lucide="clock" class="w-3 h-3 opacity-70" aria-hidden="true"></i>
									<?php echo esc_html( get_the_date() ); ?>
								</span>
							</div>
							<h4 class="text-slate-800 dark:text-slate-200 font-bold text-xs md:text-sm group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-snug">
								<?php the_title(); ?>
							</h4>
						</a>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	wp_reset_postdata();
}

/**
 * Custom comment callback to match the theme design.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Arguments.
 * @param int        $depth   Depth.
 * @return void
 */
function eghtesadran_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$is_reply = $depth > 1;
	?>
	<div <?php comment_class( $is_reply ? 'pr-8 md:pr-14 mt-6' : 'mt-6' ); ?> id="comment-<?php comment_ID(); ?>">
		<div class="flex gap-4 relative">
			<?php if ( $is_reply ) : ?>
				<!-- Indicator line for reply -->
				<div class="absolute -right-6 md:-right-10 top-0 w-6 md:w-10 h-6 border-r-2 border-b-2 border-slate-200 dark:border-slate-700 rounded-br-xl"></div>
			<?php endif; ?>
			<div class="shrink-0 <?php echo $is_reply ? 'w-8 h-8 md:w-10 md:h-10' : 'w-10 h-10 md:w-12 md:h-12'; ?> bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-500 font-bold text-lg overflow-hidden">
				<?php echo get_avatar( $comment, $is_reply ? 40 : 48 ); ?>
			</div>
			<div class="flex-1 <?php echo $is_reply ? 'bg-white dark:bg-slate-800 border border-primary/20 p-4 rounded-2xl rounded-tr-none' : 'bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 p-4 md:p-5 rounded-2xl rounded-tr-none'; ?>">
				<div class="flex items-center justify-between mb-2">
					<div class="font-bold text-sm <?php echo $is_reply ? 'text-primary' : 'text-slate-900 dark:text-white'; ?>">
						<?php comment_author(); ?>
						<?php if ( user_can( $comment->user_id, 'manage_options' ) ) : ?>
							<i data-lucide="badge-check" class="w-3.5 h-3.5 ml-1" aria-hidden="true"></i>
						<?php endif; ?>
					</div>
					<div class="text-xs font-medium text-slate-400">
						<?php printf( _x( '%s پیش', 'comment date', 'eghtesadran' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
					</div>
				</div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation text-xs text-orange-500 mb-2"><?php esc_html_e( 'دیدگاه شما در انتظار تایید است.', 'eghtesadran' ); ?></p>
				<?php endif; ?>

				<div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
					<?php comment_text(); ?>
				</div>

				<div class="mt-3 flex justify-end">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'reply_text' => '<i data-lucide="reply" class="w-3 h-3" aria-hidden="true"></i> ' . __( 'پاسخ دادن', 'eghtesadran' ),
								'add_below' => 'comment',
							)
						)
					);
					?>
				</div>
			</div>
		</div>
	<?php
}

if ( class_exists( 'Walker_Nav_Menu' ) ) {
	/**
	 * Custom walker for the desktop primary navigation.
	 */
	class Eghtesadran_Primary_Nav_Walker extends Walker_Nav_Menu {
		/**
		 * Starts a submenu level.
		 *
		 * @param string   $output Used to append additional content.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu arguments.
		 * @return void
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {
			$indent = str_repeat( "\t", $depth );

			if ( 0 === $depth ) {
				$output .= "\n$indent<ul class=\"absolute top-[38px] right-0 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 shadow-xl rounded-b-lg overflow-hidden invisible opacity-0 group-hover:visible group-hover:opacity-100 flex-col min-w-[200px] z-[60] border border-slate-100 dark:border-slate-700 translate-y-2 group-hover:translate-y-0 transition-all duration-300 flex\">\n";
				return;
			}

			$output .= "\n$indent<ul class=\"pr-4 mt-2 space-y-2 border-r border-slate-200 dark:border-slate-600\">\n";
		}

		/**
		 * Ends a submenu level.
		 *
		 * @param string   $output Used to append additional content.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu arguments.
		 * @return void
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";
		}

		/**
		 * Starts an element output.
		 *
		 * @param string   $output            Used to append additional content.
		 * @param WP_Post  $data_object       Menu item data object.
		 * @param int      $depth             Depth of menu item.
		 * @param stdClass $args              Menu arguments.
		 * @param int      $current_object_id Current object ID.
		 * @return void
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
			$item     = $data_object;
			$indent   = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$classes  = empty( $item->classes ) ? array() : (array) $item->classes;
			$has_kids = in_array( 'menu-item-has-children', $classes, true );

			if ( 0 === $depth ) {
				$item_classes = 'h-full flex items-center';

				if ( $has_kids ) {
					$item_classes .= ' relative group';
				}

				if ( 1 < (int) $item->menu_order ) {
					$item_classes .= ' nav-divider';
				}

				$output .= $indent . '<li class="' . esc_attr( $item_classes ) . '">';

				$link_classes = 'h-full flex items-center px-3 lg:px-5 hover:bg-white/10 transition-colors';

				if ( $has_kids ) {
					$link_classes .= ' gap-1';
				}
			} else {
				$output .= $indent . '<li>';
				$link_classes = 'py-2.5 px-4 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary transition-colors text-right block';

				if ( 1 === $depth ) {
					$link_classes .= ' border-b border-slate-100 dark:border-slate-700';
				}
			}

			$atts           = array();
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';
			$atts['class']  = $link_classes;
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

			$attributes = '';

			foreach ( $atts as $attr => $value ) {
				if ( empty( $value ) ) {
					continue;
				}

				$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}

			$title  = apply_filters( 'the_title', $item->title, $item->ID );
			$output .= '<a' . $attributes . '>';
			$output .= '<span>' . esc_html( $title ) . '</span>';

			if ( $has_kids ) {
				$output .= '<i data-lucide="chevron-down" class="w-3.5 h-3.5 group-hover:-rotate-180 transition-transform duration-300"></i>';
			}

			$output .= '</a>';
		}

		/**
		 * Ends an element output.
		 *
		 * @param string   $output Used to append additional content.
		 * @param WP_Post  $data_object Menu item data object.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu arguments.
		 * @return void
		 */
		public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
			$output .= "</li>\n";
		}
	}

	/**
	 * Custom walker for the mobile menu.
	 */
	class Eghtesadran_Mobile_Nav_Walker extends Walker_Nav_Menu {
		/**
		 * Starts a submenu level.
		 *
		 * @param string   $output Used to append additional content.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu arguments.
		 * @return void
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul class=\"mt-2 mr-4 pr-3 border-r border-slate-200 dark:border-slate-700 space-y-1\">\n";
		}

		/**
		 * Ends a submenu level.
		 *
		 * @param string   $output Used to append additional content.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu arguments.
		 * @return void
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";
		}

		/**
		 * Starts an element output.
		 *
		 * @param string   $output            Used to append additional content.
		 * @param WP_Post  $data_object       Menu item data object.
		 * @param int      $depth             Depth of menu item.
		 * @param stdClass $args              Menu arguments.
		 * @param int      $current_object_id Current object ID.
		 * @return void
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
			$item     = $data_object;
			$indent   = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$classes  = empty( $item->classes ) ? array() : (array) $item->classes;
			$is_home  = untrailingslashit( home_url( '/' ) ) === untrailingslashit( $item->url );
			$is_root  = 0 === $depth;
			$base     = 'py-2.5 px-4 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary text-slate-800 dark:text-slate-200 font-medium transition-colors block';
			$current  = in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true );

			if ( $is_root && ( $current || $is_home ) ) {
				$base = 'py-2.5 px-4 rounded-lg bg-red-50 text-primary dark:bg-red-950/30 dark:text-red-400 font-bold transition-colors block';
			}

			$output .= $indent . '<li>';
			$output .= sprintf(
				'<a href="%1$s" class="%2$s">%3$s</a>',
				esc_url( $item->url ),
				esc_attr( $base ),
				esc_html( apply_filters( 'the_title', $item->title, $item->ID ) )
			);
		}

		/**
		 * Ends an element output.
		 *
		 * @param string   $output Used to append additional content.
		 * @param WP_Post  $data_object Menu item data object.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu arguments.
		 * @return void
		 */
		public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
			$output .= "</li>\n";
		}
	}
}

/**
 * Returns the primary category term for a post (with fallback to default).
 *
 * @param int|WP_Post|null $post Post ID or WP_Post object. Default is current post.
 * @return WP_Term|null Category term object or null.
 */
function eghtesadran_get_primary_category( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}

	$primary_cat_id = get_post_meta( $post->ID, '_news_primary_category', true );
	if ( ! empty( $primary_cat_id ) ) {
		$term = get_term( $primary_cat_id, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			return $term;
		}
	}

	$categories = get_the_category( $post->ID );
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
		return $categories[0];
	}

	return null;
}
