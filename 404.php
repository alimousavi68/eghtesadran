<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Eghtesadran
 */

get_header(); ?>

<main class="container mx-auto px-4 xl:px-0 py-8 md:py-12">
	<div class="max-w-5xl mx-auto space-y-12">
		<!-- 404 Error Header Area -->
		<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 md:p-16 text-center relative overflow-hidden">
			 <!-- Background Decorative Elements -->
			 <div class="absolute -top-32 -right-32 w-80 h-80 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
			 <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

			 <div class="relative z-10">
				 <!-- 404 Number -->
				 <div class="text-8xl md:text-[150px] font-black text-transparent bg-clip-text bg-gradient-to-br from-primary to-primary/40 leading-none mb-6 drop-shadow-sm select-none">
					 ۴۰۴
				 </div>

				 <h1 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-5 leading-tight">
					 <?php esc_html_e( 'صفحه مورد نظر پیدا نشد!', 'eghtesadran' ); ?>
				 </h1>

				 <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base font-bold mb-10 max-w-lg mx-auto leading-loose">
					 <?php esc_html_e( 'متاسفانه صفحه‌ای که به دنبال آن هستید وجود ندارد، ممکن است آدرس آن تغییر کرده، حذف شده باشد یا آن را اشتباه وارد کرده باشید.', 'eghtesadran' ); ?>
				 </p>

				 <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-xl font-bold transition-all hover:-translate-y-1 shadow-lg shadow-primary/25 w-full sm:w-auto">
					 <i data-lucide="home" class="w-5 h-5" aria-hidden="true"></i> <?php esc_html_e( 'بازگشت به صفحه اصلی', 'eghtesadran' ); ?>
				 </a>
			 </div>
		</div>

		<!-- Trending News Section -->
		<?php
		$trending_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'meta_query'     => array(
					array(
						'key'     => '_eghtesadran_badge',
						'value'   => 'trending',
						'compare' => '=',
					),
				),
			)
		);

		if ( ! $trending_query->have_posts() ) {
			$trending_query = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => 3,
				)
			);
		}

		if ( $trending_query->have_posts() ) :
			?>
			<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 md:p-10">
				<!-- Section Header -->
				<div class="flex items-center gap-3 mb-8 pb-5 border-b border-slate-100 dark:border-slate-700">
					<div class="bg-primary/10 text-primary p-2.5 rounded-xl">
						<i data-lucide="trending-up" class="w-6 h-6" aria-hidden="true"></i>
					</div>
					<h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white"><?php esc_html_e( 'پیشنهاد مطالعه: اخبار داغ', 'eghtesadran' ); ?></h2>
				</div>

				<!-- News Grid -->
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
					<?php
					while ( $trending_query->have_posts() ) :
						$trending_query->the_post();
						?>
						<div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden group hover:border-primary/50 transition-colors">
							<a href="<?php the_permalink(); ?>" class="block relative aspect-[16/10] overflow-hidden">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105' ) ); ?>
								<?php else : ?>
									<div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center transition-transform duration-700 group-hover:scale-105">
										<i data-lucide="image" class="w-10 h-10 text-slate-300 dark:text-slate-500"></i>
									</div>
								<?php endif; ?>
								<div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
							</a>
							<div class="p-5">
								<div class="flex items-center justify-between mb-3 text-[10px] font-bold text-slate-400 dark:text-slate-500">
									<span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3" aria-hidden="true"></i> <?php echo get_the_date(); ?></span>
									<span class="flex items-center gap-1 text-primary"><i data-lucide="flame" class="w-3 h-3" aria-hidden="true"></i> <?php esc_html_e( 'داغ', 'eghtesadran' ); ?></span>
								</div>
								<h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 group-hover:text-primary dark:group-hover:text-red-400 transition-colors mb-3 leading-snug line-clamp-2">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
							</div>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>

				<div class="mt-8 text-center">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 px-6 py-3 rounded-xl font-bold text-sm transition-colors">
						<?php esc_html_e( 'مشاهده اخبار بیشتر', 'eghtesadran' ); ?> <i data-lucide="arrow-left" class="w-4 h-4" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
