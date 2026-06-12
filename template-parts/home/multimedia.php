<?php
/**
 * Multimedia section for the homepage.
 *
 * @package Eghtesadran
 */

$section_title  = get_theme_mod( 'eghtesadran_multimedia_title', __( 'چندرسانه‌ای', 'eghtesadran' ) );
$section_desc   = get_theme_mod( 'eghtesadran_multimedia_desc', __( 'گزارش‌های ویدئویی و تصویری اختصاصی', 'eghtesadran' ) );
$posts_per_page = get_theme_mod( 'eghtesadran_multimedia_posts_per_page', 3 );
$orderby        = get_theme_mod( 'eghtesadran_multimedia_orderby', 'date' );
$order          = get_theme_mod( 'eghtesadran_multimedia_order', 'DESC' );

$multimedia_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby,
		'order'          => $order,
		'meta_query'     => array(
			array(
				'key'     => '_news_content_type',
				'value'   => array( 'video', 'audio', 'photo_report' ),
				'compare' => 'IN',
			),
		),
	)
);

if ( ! $multimedia_query->have_posts() ) {
	return;
}
?>

<!-- 4. MULTIMEDIA SECTION -->
<div class="eghtesadran-multimedia-section-wrapper w-full mb-12 mt-4">
	<section class="bg-[#0a0f18] rounded-2xl py-10 px-5 md:px-8 relative overflow-hidden shadow-lg border border-slate-800/80">
		<div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full mix-blend-screen filter blur-[100px] opacity-20 pointer-events-none"></div>
		<div class="absolute bottom-0 left-0 w-96 h-96 bg-red-650/5 rounded-full mix-blend-screen filter blur-[100px] opacity-20 pointer-events-none"></div>

		<div class="relative z-10 w-full">
			<!-- Header -->
			<div class="flex items-center justify-between mb-6 border-b border-slate-800/70 pb-3">
				<div>
					<h2 class="text-2xl font-extrabold text-white flex items-center gap-2">
						<i data-lucide="video" class="w-6 h-6 text-primary" aria-hidden="true"></i>
						<?php echo esc_html( $section_title ); ?>
					</h2>
					<?php if ( ! empty( $section_desc ) ) : ?>
						<p class="text-slate-400 text-xs mt-1 font-medium"><?php echo esc_html( $section_desc ); ?></p>
					<?php endif; ?>
					<!-- Accent bar -->
					<div class="flex h-1 mt-1.5 mb-1 w-24 rounded-full overflow-hidden">
						<div class="w-1/3 bg-primary"></div>
						<div class="w-2/3 bg-slate-800"></div>
					</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="group hidden sm:flex text-xs font-bold text-slate-400 hover:text-white transition-colors gap-0.5 items-center px-4 py-2 rounded-full border border-slate-800 hover:border-slate-700 hover:bg-slate-900/50">
					<?php esc_html_e( 'مشاهده همه', 'eghtesadran' ); ?>
					<i data-lucide="chevron-left" class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" aria-hidden="true"></i>
				</a>
			</div>

			<!-- Video Grid -->
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
				<?php
				$count = 0;
				while ( $multimedia_query->have_posts() ) :
					$multimedia_query->the_post();
					$count++;
					$content_type = get_post_meta( get_the_ID(), '_news_content_type', true );
					if ( 'audio' === $content_type ) {
						$duration = get_post_meta( get_the_ID(), '_news_audio_duration', true );
					} else {
						$duration = get_post_meta( get_the_ID(), '_news_video_duration', true );
					}
					?>
					<?php if ( 1 === $count ) : ?>
						<!-- Main Video (8 cols) -->
						<div class="lg:col-span-8 group cursor-pointer rounded-2xl overflow-hidden relative border border-slate-800">
							<a href="<?php the_permalink(); ?>" class="block">
								<div class="h-[300px] sm:aspect-[16/9] lg:aspect-auto lg:h-[420px] relative overflow-hidden flex items-center justify-center bg-slate-950">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'eghtesadran-hero', array( 'class' => 'w-full h-full object-cover opacity-55 group-hover:scale-103 transition-transform duration-1000' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder-dark.jpg' ) ); ?>" class="w-full h-full object-cover opacity-55" alt="<?php the_title_attribute(); ?>" />
									<?php endif; ?>

									<!-- Play button / Headphones / Image Icon -->
									<div class="absolute w-14 h-14 md:w-16 md:h-16 bg-primary rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-xl shadow-red-900/30 border-2 border-red-400/40">
										<?php if ( 'audio' === $content_type ) : ?>
											<i data-lucide="headphones" class="text-white w-5 h-5 md:w-6 md:h-6" aria-hidden="true"></i>
										<?php elseif ( 'photo_report' === $content_type ) : ?>
											<i data-lucide="camera" class="text-white w-5 h-5 md:w-6 md:h-6" aria-hidden="true"></i>
										<?php else : ?>
											<i data-lucide="play" class="text-white ml-1.5 w-5 h-5 md:w-6 md:h-6" fill="white" aria-hidden="true"></i>
										<?php endif; ?>
									</div>

									<!-- Text Overlay -->
									<div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-slate-950 via-slate-900/80 to-transparent p-4 md:p-6 pt-24">
										<h3 class="text-white font-extrabold text-sm md:text-xl leading-snug md:leading-relaxed group-hover:text-red-400 transition-colors">
											<?php the_title(); ?>
										</h3>
										<div class="flex gap-4 mt-3 text-slate-400 text-[11px] font-bold">
											<?php if ( $duration ) : ?>
												<span class="flex items-center gap-1.5 bg-slate-900/60 border border-slate-800 px-2 py-0.5 rounded-md">
													<?php if ( 'audio' === $content_type ) : ?>
														<i data-lucide="headphones" class="w-3.5 h-3.5 text-primary" aria-hidden="true"></i>
													<?php else : ?>
														<i data-lucide="play" class="w-3.5 h-3.5 text-primary" aria-hidden="true"></i>
													<?php endif; ?>
													<?php echo esc_html( $duration ); ?>
												</span>
											<?php endif; ?>
											<span class="flex items-center"><?php echo esc_html( get_the_date() ); ?></span>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="lg:col-span-4 flex flex-col gap-6">
					<?php else : ?>
						<!-- Side Multimedia Items -->
						<div class="group cursor-pointer rounded-2xl overflow-hidden relative h-[197px] bg-slate-950 border border-slate-800">
							<a href="<?php the_permalink(); ?>" class="block h-full">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'eghtesadran-thumb', array( 'class' => 'w-full h-full object-cover opacity-60 group-hover:scale-108 transition-all duration-700' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder-dark.jpg' ) ); ?>" class="w-full h-full object-cover opacity-60" alt="<?php the_title_attribute(); ?>" />
								<?php endif; ?>
								
								<div class="absolute w-8 h-8 bg-primary/80 rounded-full flex items-center justify-center top-3 right-3 z-20">
									<?php if ( 'audio' === $content_type ) : ?>
										<i data-lucide="headphones" class="text-white w-3.5 h-3.5" aria-hidden="true"></i>
									<?php elseif ( 'photo_report' === $content_type ) : ?>
										<i data-lucide="camera" class="text-white w-3.5 h-3.5" aria-hidden="true"></i>
									<?php else : ?>
										<i data-lucide="play" class="text-white ml-0.5 w-3.5 h-3.5" fill="white" aria-hidden="true"></i>
									<?php endif; ?>
								</div>

								<div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent flex flex-col justify-end p-4">
									<h3 class="text-white font-bold text-xs md:text-sm leading-snug md:leading-relaxed group-hover:text-red-400 transition-colors">
										<?php the_title(); ?>
									</h3>
								</div>
							</a>
						</div>
					<?php endif; ?>
				<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
