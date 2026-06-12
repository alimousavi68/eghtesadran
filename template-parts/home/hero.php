<?php
/**
 * Hero section for the homepage.
 *
 * @package Eghtesadran
 */

// Main Hero Query (1 post)
$hero_main_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'     => '_eghtesadran_badge',
				'value'   => 'featured',
				'compare' => '=',
			),
		),
	)
);

// Fallback if no featured post
if ( ! $hero_main_query->have_posts() ) {
	$hero_main_query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 1,
		)
	);
}

// Side News Query (5 posts, excluding the main one)
$exclude_id = 0;
if ( $hero_main_query->have_posts() ) {
	$exclude_id = $hero_main_query->posts[0]->ID;
}

$hero_side_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 5,
		'post__not_in'   => array( $exclude_id ),
		// Prefer trending/breaking for side list
		'meta_key'       => '_eghtesadran_badge',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
	)
);

// Fallback if side query has no posts
if ( ! $hero_side_query->have_posts() ) {
	$hero_side_query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 5,
			'post__not_in'   => array( $exclude_id ),
		)
	);
}
?>

<!-- 2. HERO SECTION -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6 bg-white dark:bg-slate-800 p-4 md:p-6 rounded-none md:rounded-2xl -mx-4 md:mx-0 border-y md:border-x border-slate-100 dark:border-slate-700 shadow-sm md:shadow-sm">
	<!-- Main News (8 cols) -->
	<div class="lg:col-span-8 group flex flex-col justify-between">
		<?php if ( $hero_main_query->have_posts() ) : ?>
			<?php while ( $hero_main_query->have_posts() ) : $hero_main_query->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="block">
					<div class="relative rounded-xl overflow-hidden aspect-[16/9] lg:aspect-[16/7.5] mb-5 shadow-inner">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'eghtesadran-hero', array( 'class' => 'w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105' ) ); ?>
						<?php else : ?>
							<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
						<?php endif; ?>
						<div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent pointer-events-none"></div>
					</div>
					<div class="px-1">
						<div class="flex items-center gap-3 mb-3">
							<?php
							$categories = get_the_category();
							if ( ! empty( $categories ) ) :
								?>
								<span class="text-white font-extrabold text-xs bg-primary px-3 py-1 rounded-full"><?php echo esc_html( $categories[0]->name ); ?></span>
							<?php endif; ?>
							<span class="text-slate-400 dark:text-slate-500 text-xs flex items-center">
								<i data-lucide="clock" class="w-3.5 h-3.5 ml-1" aria-hidden="true"></i>
								<?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) . ' ' . esc_html__( 'پیش', 'eghtesadran' ); ?>
							</span>
						</div>
						<h2 class="text-xl md:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white leading-snug md:leading-[1.3] mb-4 group-hover:text-primary dark:group-hover:text-red-400 transition-colors">
							<?php the_title(); ?>
						</h2>
						<div class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm md:text-base text-justify">
							<?php
							$lead = get_post_meta( get_the_ID(), '_eghtesadran_lead', true );
							if ( $lead ) {
								echo esc_html( $lead );
							} else {
								echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) );
							}
							?>
						</div>
					</div>
				</a>
			<?php endwhile; wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>

	<!-- Side News ("اخبار مهم" widget - 4 cols) -->
	<div class="lg:col-span-4 flex flex-col lg:border-r border-slate-100 dark:border-slate-700 lg:pr-6 mt-6 lg:mt-0 pt-6 lg:pt-0 border-t lg:border-t-0">
		<div class="mb-4">
			<h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
				<i data-lucide="trending-up" class="w-5 h-5 text-primary" aria-hidden="true"></i>
				<?php esc_html_e( 'مهم ترین اخبار', 'eghtesadran' ); ?>
			</h3>
		</div>

		<div class="flex flex-col justify-between flex-1 gap-4">
			<?php if ( $hero_side_query->have_posts() ) : ?>
				<?php while ( $hero_side_query->have_posts() ) : $hero_side_query->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="group flex gap-4 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-900/60 border border-transparent hover:border-slate-100 dark:hover:border-slate-700 transition-all">
						<div class="w-20 h-20 rounded-lg overflow-hidden shrink-0">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'eghtesadran-thumb', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform' ) ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
							<?php endif; ?>
						</div>
						<div class="flex flex-col justify-center">
							<div class="flex items-center justify-between gap-2 mb-1.5">
								<?php
								$categories = get_the_category();
								if ( ! empty( $categories ) ) :
									?>
									<span class="text-primary text-[10px] font-black"><?php echo esc_html( $categories[0]->name ); ?></span>
								<?php endif; ?>
								<span class="text-slate-400 dark:text-slate-500 text-[10px]">
									<?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
								</span>
							</div>
							<h4 class="text-slate-800 dark:text-slate-200 font-medium leading-snug text-sm lg:text-[14px] group-hover:text-primary dark:group-hover:text-red-400 transition-colors">
								<?php the_title(); ?>
							</h4>
						</div>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
