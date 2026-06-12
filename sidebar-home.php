<?php
/**
 * The sidebar containing the main widget area for the homepage.
 *
 * @package Eghtesadran
 */
?>
<!-- Sidebar column -->
<aside class="md:col-span-4 lg:col-span-3 space-y-8 shrink-0 w-full flex flex-col self-start sticky top-28">

	<?php if ( is_active_sidebar( 'sidebar-home' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-home' ); ?>
	<?php else : ?>

		<!-- Market Dashboard Widget (پیشخوان بازار) -->
		<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
			<div class="mb-4">
				<h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
					<i data-lucide="bar-chart-2" class="w-5 h-5 text-primary" aria-hidden="true"></i>
					<?php esc_html_e( 'پیشخوان بازار', 'eghtesadran' ); ?>
				</h3>
			</div>

			<div class="grid grid-cols-3 gap-3">
				<?php
				$market_items = array(
					array( 'label' => 'فلزات اساسی', 'icon' => 'layers', 'slug' => 'metals' ),
					array( 'label' => 'پتروشیمی', 'icon' => 'droplet', 'slug' => 'petrochemical' ),
					array( 'label' => 'ارز و طلا', 'icon' => 'coins', 'slug' => 'gold-currency' ),
					array( 'label' => 'بورس و اوراق', 'icon' => 'line-chart', 'slug' => 'bours' ),
					array( 'label' => 'خودرو', 'icon' => 'car', 'slug' => 'auto' ),
					array( 'label' => 'انرژی', 'icon' => 'zap', 'slug' => 'energy' ),
				);

				foreach ( $market_items as $item ) :
					$cat = get_category_by_slug( $item['slug'] );
					$url = $cat ? get_category_link( $cat->term_id ) : '#';
					?>
					<a href="<?php echo esc_url( $url ); ?>" class="group flex flex-col items-center justify-center pt-5 pb-4 px-1 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:bg-white dark:hover:bg-slate-800 hover:border-primary hover:-translate-y-[2px] outline-none">
						<i data-lucide="<?php echo esc_attr( $item['icon'] ); ?>" class="w-[26px] h-[26px] text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors duration-300" stroke-width="1.5" aria-hidden="true"></i>
						<span class="text-slate-700 dark:text-slate-200 text-[11px] sm:text-[11.5px] font-bold mt-5 text-center transition-colors duration-300 group-hover:text-primary">
							<?php echo esc_html( $item['label'] ); ?>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Latest News Feed (آخرین اخبار) -->
		<div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
			<div class="flex items-center justify-between mb-4">
				<div>
					<h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
						<i data-lucide="clock" class="w-5 h-5 text-primary" aria-hidden="true"></i>
						<?php esc_html_e( 'آخرین اخبار', 'eghtesadran' ); ?>
					</h3>
				</div>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>" class="group flex text-[10px] font-bold text-slate-500 dark:text-slate-400 hover:text-primary transition-colors gap-0.5 items-center">
					<?php esc_html_e( 'مشاهده همه', 'eghtesadran' ); ?>
					<i data-lucide="chevron-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" aria-hidden="true"></i>
				</a>
			</div>

			<div class="flex flex-col gap-3.5">
				<?php
				$latest_query = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 10,
					)
				);

				if ( $latest_query->have_posts() ) :
					while ( $latest_query->have_posts() ) :
						$latest_query->the_post();
						$badge = get_post_meta( get_the_ID(), '_eghtesadran_badge', true );
						?>
						<div class="flex gap-3 items-start group cursor-pointer border-b border-slate-50 dark:border-slate-750 pb-3 last:border-0 last:pb-0">
							<a href="<?php the_permalink(); ?>" class="flex gap-3 items-start w-full">
								<?php if ( 'breaking' === $badge ) : ?>
									<div class="bg-primary text-white font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0 animate-pulse">
										<?php esc_html_e( 'فوری', 'eghtesadran' ); ?>
									</div>
								<?php else : ?>
									<div class="bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 font-extrabold text-[10px] px-2 py-0.5 rounded w-12 text-center shrink-0">
										<?php echo esc_html( get_the_time( 'H:i' ) ); ?>
									</div>
								<?php endif; ?>
								<h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 group-hover:text-primary dark:group-hover:text-red-400 transition-colors leading-relaxed">
									<?php the_title(); ?>
								</h4>
							</a>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>
		</div>

		<!-- Notes Widget -->
		<?php
		the_widget(
			'Eghtesadran_Widget_Note',
			array(
				'title'       => __( 'یادداشت‌ها', 'eghtesadran' ),
				'posts_count' => 3,
			),
			array(
				'before_widget' => '',
				'after_widget'  => '',
			)
		);
		?>

		<!-- Interviews Widget -->
		<?php
		the_widget(
			'Eghtesadran_Widget_Interview',
			array(
				'title'       => __( 'مصاحبه‌ها', 'eghtesadran' ),
				'posts_count' => 3,
			),
			array(
				'before_widget' => '',
				'after_widget'  => '',
			)
		);
		?>

		<!-- Sidebar Ads Widget -->
		<div class="relative bg-slate-200 rounded-2xl overflow-hidden aspect-[16/11] flex items-center justify-center cursor-pointer group shadow-sm border border-slate-100 dark:border-slate-700">
			<div class="absolute top-3 left-3 bg-black/55 backdrop-blur text-white text-[9px] px-2.5 py-1 rounded-md z-10 font-bold">
				<?php esc_html_e( 'تبلیغات', 'eghtesadran' ); ?>
			</div>
			<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/bours-26.jpg' ) ); ?>" alt="<?php esc_attr_e( 'تبلیغات', 'eghtesadran' ); ?>" class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-all duration-700" />
			<div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent flex flex-col justify-end p-5">
				<span class="text-white font-extrabold text-base md:text-lg mb-1.5 leading-snug">صندوق‌های نوین سرمایه‌گذاری املاک</span>
				<span class="text-red-300 text-xs font-bold flex items-center gap-1">مدیریت ریسک هوشمند مسکن <i data-lucide="arrow-left" class="w-3.5 h-3.5" aria-hidden="true"></i></span>
			</div>
		</div>

	<?php endif; ?>

</aside>
