<?php
/**
 * Template part for displaying single posts.
 *
 * @package Eghtesadran
 */
?>
	<div class="lg:col-span-8 space-y-6">
		<?php
		while ( have_posts() ) :
			the_post();
			$post_id = get_the_ID();
			$badge   = get_post_meta( $post_id, '_eghtesadran_badge', true );
			$lead    = get_post_meta( $post_id, '_eghtesadran_lead', true );

			$badges = array(
				'featured' => __( 'اخبار ویژه', 'eghtesadran' ),
				'breaking' => __( 'خبر فوری', 'eghtesadran' ),
				'trending' => __( 'خبر داغ', 'eghtesadran' ),
			);
			?>
			<!-- Article Card -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 md:p-8 mb-8 article-container relative' ); ?>>

			<!-- Print Logo -->
			<div class="hidden print:block print-logo text-right mb-8">
				<?php
				if ( has_custom_logo() ) {
					// Wrap the_custom_logo() output to control sizing in print
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					$logo_url       = wp_get_attachment_image_url( $custom_logo_id, 'full' );
					if ( $logo_url ) {
						echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" style="height:3rem;width:auto;display:inline-block;">';
					} else {
						the_custom_logo();
					}
				} else {
					?>
					<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/logo.webp' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" style="height:3rem;width:auto;display:inline-block;">
					<?php
				}
				?>
			</div>

			<!-- Breadcrumbs -->
			<nav class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700 print:hidden">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary transition-colors flex items-center gap-1">
					<i data-lucide="home" class="w-3.5 h-3.5" aria-hidden="true"></i> <?php esc_html_e( 'صفحه اصلی', 'eghtesadran' ); ?>
				</a>
				<?php
				$primary_cat = eghtesadran_get_primary_category( $post_id );
				if ( $primary_cat ) :
					?>
					<span>/</span>
					<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="hover:text-primary transition-colors"><?php echo esc_html( $primary_cat->name ); ?></a>
				<?php endif; ?>
			</nav>

			<!-- Article Header -->
			<header class="mb-8">
				<div class="mb-4">
					<?php 
					if ( $badge && isset( $badges[$badge] ) ) : 
						$badge_class = 'bg-red-500/10 text-primary border border-red-100 dark:border-red-950/30';
						if ( 'featured' === $badge ) {
							$badge_class = 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-950/30';
						} elseif ( 'trending' === $badge ) {
							$badge_class = 'bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-100 dark:border-orange-950/30';
						}
						?>
						<span class="inline-block <?php echo esc_attr( $badge_class ); ?> text-xs font-black px-3 py-1 rounded-full mb-3"><?php echo esc_html( $badges[$badge] ); ?></span>
					<?php endif; ?>
					<?php 
					$rotiter = get_post_meta( $post_id, '_news_rotiter', true );
					if ( ! empty( $rotiter ) ) :
						?>
						<span class="block text-xs md:text-sm text-slate-400 dark:text-slate-500 font-bold mb-5"><?php echo esc_html( $rotiter ); ?></span>
					<?php endif; ?>
					<h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white leading-[1.8] md:leading-loose mb-5">
						<?php the_title(); ?>
					</h1>
					<!-- Lead Paragraph -->
					<?php if ( $lead ) : ?>
						<p class="text-slate-600 dark:text-slate-300 font-bold text-sm md:text-base leading-relaxed md:leading-loose text-justify border-r-4 border-primary pr-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-l-xl">
							<?php echo esc_html( $lead ); ?>
						</p>
					<?php endif; ?>
				</div>

				<!-- Meta Bar -->
				<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-y border-slate-100 dark:border-slate-700 text-xs font-bold text-slate-500">
					<div class="flex flex-wrap items-center gap-4">
						<div class="flex items-center gap-1.5">
							<i data-lucide="calendar" class="w-4 h-4 text-slate-400" aria-hidden="true"></i> <?php echo esc_html( get_the_date() ); ?>
						</div>

					</div>
					<div class="flex flex-wrap items-center gap-2 print:hidden">
						<!-- Accessibility & Tools (Keep structure) -->
						<div class="flex items-center bg-slate-50 dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
							<button id="btn-font-plus" class="w-8 h-8 flex items-center justify-center hover:bg-primary hover:text-white transition-colors" title="<?php esc_attr_e( 'افزایش فونت', 'eghtesadran' ); ?>">
								<span class="font-bold text-sm">A+</span>
							</button>
							<div class="w-px h-4 bg-slate-200 dark:border-slate-700"></div>
							<button id="btn-font-minus" class="w-8 h-8 flex items-center justify-center hover:bg-primary hover:text-white transition-colors" title="<?php esc_attr_e( 'کاهش فونت', 'eghtesadran' ); ?>">
								<span class="font-bold text-sm">A-</span>
							</button>
						</div>

						<div class="flex items-center bg-slate-50 dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700">
							<button id="btn-line-plus" class="w-8 h-8 flex items-center justify-center hover:bg-primary hover:text-white transition-colors" title="<?php esc_attr_e( 'افزایش فاصله', 'eghtesadran' ); ?>">
								<i data-lucide="align-justify" class="w-3.5 h-3.5" aria-hidden="true"></i><span class="text-[10px] font-bold ml-0.5">+</span>
							</button>
							<div class="w-px h-4 bg-slate-200 dark:border-slate-700"></div>
							<button id="btn-line-minus" class="w-8 h-8 flex items-center justify-center hover:bg-primary hover:text-white transition-colors" title="<?php esc_attr_e( 'کاهش فاصله', 'eghtesadran' ); ?>">
								<i data-lucide="align-justify" class="w-3.5 h-3.5" aria-hidden="true"></i><span class="text-[10px] font-bold ml-0.5">-</span>
							</button>
						</div>

						<div class="h-4 w-px bg-slate-200 dark:border-slate-700 mx-1"></div>

						<button onclick="window.print()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 hover:bg-primary hover:text-white transition-colors border border-slate-200 dark:border-slate-700" title="<?php esc_attr_e( 'چاپ', 'eghtesadran' ); ?>">
							<i data-lucide="printer" class="w-4 h-4" aria-hidden="true"></i>
						</button>
						<a href="https://wa.me/?text=<?php echo rawurlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 hover:bg-[#25D366] hover:text-white transition-colors border border-slate-200 dark:border-slate-700">
							<i data-lucide="message-circle" class="w-4 h-4" aria-hidden="true"></i>
						</a>
						<a href="https://t.me/share/url?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 hover:bg-[#0088cc] hover:text-white transition-colors border border-slate-200 dark:border-slate-700">
							<i data-lucide="send" class="w-4 h-4" aria-hidden="true"></i>
						</a>
						<a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode( get_the_title() ); ?>&url=<?php echo rawurlencode( get_permalink() ); ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 hover:bg-[#1DA1F2] hover:text-white transition-colors border border-slate-200 dark:border-slate-700">
							<i data-lucide="twitter" class="w-4 h-4" aria-hidden="true"></i>
						</a>
					</div>
				</div>
			</header>

			<!-- High-Priority Player or Featured Image -->
			<?php if ( 'video' === $content_type ) : 
				$v_source = get_post_meta( $post_id, '_news_video_source_type', true );
				$v_embed  = get_post_meta( $post_id, '_news_video_embed_code', true );
				$v_hq     = get_post_meta( $post_id, '_news_video_hq_link', true );
				$v_lq     = get_post_meta( $post_id, '_news_video_lq_link', true );
				$v_direct = $v_lq ? $v_lq : $v_hq;
				?>
				<div class="mb-6 rounded-2xl overflow-hidden aspect-[16/9] shadow-md bg-slate-950 flex items-center justify-center relative border border-slate-800">
					<?php if ( 'embed' === $v_source && $v_embed ) : ?>
						<div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full"><?php echo $v_embed; ?></div>
					<?php elseif ( 'direct' === $v_source && $v_direct ) : ?>
						<video controls class="w-full h-full object-cover" poster="<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'full' ) ); ?>">
							<source src="<?php echo esc_url( $v_direct ); ?>" type="video/mp4">
							<?php esc_html_e( 'مرورگر شما از تگ ویدیو پشتیبانی نمی‌کند.', 'eghtesadran' ); ?>
						</video>
					<?php else : ?>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover opacity-60' ) ); ?>
						<?php endif; ?>
						<div class="absolute inset-0 flex items-center justify-center">
							<i data-lucide="play-circle" class="w-16 h-16 text-white/50"></i>
						</div>
					<?php endif; ?>
				</div>

				<!-- Video Download Box -->
				<?php if ( $v_hq || $v_lq ) : ?>
					<div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 text-sm">
						<div class="flex items-center gap-2.5 font-bold text-slate-800 dark:text-slate-200">
							<i data-lucide="download" class="w-5 h-5 text-primary"></i>
							<span><?php esc_html_e( 'دریافت و دانلود فایل ویدیو:', 'eghtesadran' ); ?></span>
						</div>
						<div class="flex gap-2 w-full sm:w-auto">
							<?php if ( $v_hq ) : ?>
								<a href="<?php echo esc_url( $v_hq ); ?>" target="_blank" download class="flex-1 sm:flex-none text-center bg-primary hover:bg-primary-hover text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
									<?php esc_html_e( 'کیفیت عالی (HQ)', 'eghtesadran' ); ?>
								</a>
							<?php endif; ?>
							<?php if ( $v_lq ) : ?>
								<a href="<?php echo esc_url( $v_lq ); ?>" target="_blank" download class="flex-1 sm:flex-none text-center bg-slate-250 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold px-4 py-2 rounded-lg transition-colors border border-slate-300 dark:border-slate-600">
									<?php esc_html_e( 'کیفیت معمولی (LQ)', 'eghtesadran' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

			<?php elseif ( 'audio' === $content_type ) : 
				$audio_file_id  = get_post_meta( $post_id, '_news_audio_file_id', true );
				$audio_duration = get_post_meta( $post_id, '_news_audio_duration', true );
				$audio_url      = $audio_file_id ? wp_get_attachment_url( $audio_file_id ) : '';
				?>
				<?php if ( $audio_url ) : ?>
					<div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row items-center gap-6 shadow-sm mb-6">
						<div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
							<i data-lucide="headphones" class="w-8 h-8 text-primary"></i>
						</div>
						<div class="flex-1 w-full">
							<h4 class="text-base font-extrabold text-slate-850 dark:text-slate-200 mb-1"><?php esc_html_e( 'پخش صوتی خبر / پادکست', 'eghtesadran' ); ?></h4>
							<?php if ( $audio_duration ) : ?>
								<div class="text-xs font-mono text-slate-450 dark:text-slate-500 mb-3"><?php esc_html_e( 'مدت زمان:', 'eghtesadran' ); ?> <?php echo esc_html( $audio_duration ); ?></div>
							<?php endif; ?>
							<audio controls class="w-full h-10 outline-none rounded-lg focus:ring-2 focus:ring-primary">
								<source src="<?php echo esc_url( $audio_url ); ?>" type="audio/mpeg">
								<?php esc_html_e( 'مرورگر شما از تگ صوتی پشتیبانی نمی‌کند.', 'eghtesadran' ); ?>
							</audio>
						</div>
						<a href="<?php echo esc_url( $audio_url ); ?>" target="_blank" download class="shrink-0 flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-5 py-3 rounded-xl transition-colors w-full md:w-auto justify-center">
							<i data-lucide="download" class="w-4 h-4"></i> <?php esc_html_e( 'دانلود فایل صوتی', 'eghtesadran' ); ?>
						</a>
					</div>
				<?php endif; ?>

			<?php elseif ( has_post_thumbnail() ) : ?>
				<figure class="mb-8 rounded-2xl overflow-hidden shadow-sm">
					<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto object-cover aspect-[16/9]' ) ); ?>
				</figure>
			<?php endif; ?>

			<!-- Article Body -->
			<div class="article-content text-slate-800 dark:text-slate-200 text-sm md:text-base leading-loose text-justify space-y-6">
				<?php the_content(); ?>

				<?php
				$source_name = get_post_meta( $post_id, '_news_source_name', true );
				$source_link = get_post_meta( $post_id, '_news_source_link', true );
				if ( ! empty( $source_name ) ) :
					?>
					<div class="news-source-meta mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 text-sm font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1.5 print:hidden">
						<span><?php esc_html_e( 'منبع:', 'eghtesadran' ); ?></span>
						<?php if ( ! empty( $source_link ) ) : ?>
							<a href="<?php echo esc_url( $source_link ); ?>" target="_blank" rel="nofollow noopener" class="text-primary hover:underline transition-colors"><?php echo esc_html( $source_name ); ?></a>
						<?php else : ?>
							<span class="text-slate-700 dark:text-slate-350"><?php echo esc_html( $source_name ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<!-- Photo Report Gallery Display -->
				<?php if ( 'photo_report' === $content_type ) : 
					$gallery_images_str = get_post_meta( $post_id, '_news_gallery_images', true );
					if ( ! empty( $gallery_images_str ) ) :
						$gallery_ids = explode( ',', $gallery_images_str );
						?>
						<hr class="my-8 border-slate-100 dark:border-slate-700">
						<div class="photo-report-gallery-wrapper mt-8">
							<div class="flex items-center gap-2 mb-4">
								<i data-lucide="camera" class="w-5 h-5 text-primary"></i>
								<h3 class="text-base font-extrabold text-slate-900 dark:text-white"><?php esc_html_e( 'گزارش تصویری / گالری عکس', 'eghtesadran' ); ?></h3>
							</div>
							
							<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="eghtesadran-gallery-grid">
								<?php 
								foreach ( $gallery_ids as $index => $img_id ) : 
									$img_url_full = wp_get_attachment_image_url( $img_id, 'full' );
									$img_url_thumb = wp_get_attachment_image_url( $img_id, 'medium' );
									if ( ! $img_url_full ) continue;
									?>
									<a href="<?php echo esc_url( $img_url_full ); ?>" data-index="<?php echo esc_attr( $index ); ?>" class="eghtesadran-gallery-link aspect-[4/3] rounded-xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-700 relative group block bg-slate-900">
										<img src="<?php echo esc_url( $img_url_thumb ); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="<?php echo esc_attr( get_the_title() ); ?>">
										<div class="absolute inset-0 bg-black/0 group-hover:bg-black/35 transition-all flex items-center justify-center">
											<i data-lucide="zoom-in" class="text-white opacity-0 group-hover:opacity-100 transition-opacity w-6 h-6"></i>
										</div>
									</a>
								<?php endforeach; ?>
							</div>
						</div>

						<!-- Lightbox Modal Slider -->
						<div id="eghtesadran-lightbox" class="fixed inset-0 z-[9999] hidden bg-black/95 backdrop-blur-sm flex flex-col justify-between p-4 transition-all duration-300 opacity-0">
							<!-- Top Bar -->
							<div class="flex justify-between items-center text-white p-2">
								<div class="text-sm font-bold bg-white/10 px-4 py-1.5 rounded-full"><span id="lightbox-current">1</span> / <span id="lightbox-total">1</span></div>
								<button id="lightbox-close" class="p-2 hover:bg-white/15 rounded-full transition-colors outline-none cursor-pointer">
									<i data-lucide="x" class="w-6 h-6"></i>
								</button>
							</div>
							
							<!-- Main Slider Area -->
							<div class="flex-1 flex items-center justify-between relative max-w-5xl mx-auto w-full my-4">
								<button id="lightbox-prev" class="absolute right-0 sm:right-4 z-50 p-3 bg-white/10 hover:bg-white/20 text-white rounded-full transition-colors outline-none cursor-pointer">
									<i data-lucide="chevron-right" class="w-6 h-6"></i>
								</button>
								
								<div class="w-full h-full flex items-center justify-center p-4">
									<img id="lightbox-img" src="" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl transition-all duration-300 transform scale-95" alt="Gallery image">
								</div>
								
								<button id="lightbox-next" class="absolute left-0 sm:left-4 z-50 p-3 bg-white/10 hover:bg-white/20 text-white rounded-full transition-colors outline-none cursor-pointer">
									<i data-lucide="chevron-left" class="w-6 h-6"></i>
								</button>
							</div>
							
							<!-- Captions / Bottom Area -->
							<div class="text-center text-white pb-6 px-4">
								<p id="lightbox-caption" class="text-sm font-bold text-slate-200 max-w-2xl mx-auto"><?php the_title(); ?></p>
							</div>
						</div>

						<script>
						document.addEventListener('DOMContentLoaded', function() {
							var galleryLinks = document.querySelectorAll('.eghtesadran-gallery-link');
							var lightbox = document.getElementById('eghtesadran-lightbox');
							var lightboxImg = document.getElementById('lightbox-img');
							var currentSpan = document.getElementById('lightbox-current');
							var totalSpan = document.getElementById('lightbox-total');
							var closeBtn = document.getElementById('lightbox-close');
							var prevBtn = document.getElementById('lightbox-prev');
							var nextBtn = document.getElementById('lightbox-next');
							
							var currentIndex = 0;
							var images = [];
							
							galleryLinks.forEach(function(link) {
								images.push(link.getAttribute('href'));
								link.addEventListener('click', function(e) {
									e.preventDefault();
									currentIndex = parseInt(link.getAttribute('data-index'));
									openLightbox();
								});
							});
							
							totalSpan.textContent = images.length;
							
							function openLightbox() {
								updateImage();
								lightbox.classList.remove('hidden');
								setTimeout(function() {
									lightbox.style.opacity = '1';
									lightboxImg.classList.remove('scale-95');
									lightboxImg.classList.add('scale-100');
								}, 50);
								document.body.style.overflow = 'hidden';
							}
							
							function closeLightbox() {
								lightbox.style.opacity = '0';
								lightboxImg.classList.remove('scale-100');
								lightboxImg.classList.add('scale-95');
								setTimeout(function() {
									lightbox.classList.add('hidden');
								}, 300);
								document.body.style.overflow = '';
							}
							
							function updateImage() {
								lightboxImg.style.opacity = '0';
								setTimeout(function() {
									lightboxImg.src = images[currentIndex];
									currentSpan.textContent = currentIndex + 1;
									lightboxImg.style.opacity = '1';
								}, 150);
							}
							
							function prevImage() {
								currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
								updateImage();
							}
							
							function nextImage() {
								currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
								updateImage();
							}
							
							closeBtn.addEventListener('click', closeLightbox);
							prevBtn.addEventListener('click', prevImage);
							nextBtn.addEventListener('click', nextImage);
							
							// Keyboard Bindings
							document.addEventListener('keydown', function(e) {
								if (lightbox.classList.contains('hidden')) return;
								if (e.key === 'Escape') closeLightbox();
								if (e.key === 'ArrowRight') prevImage();
								if (e.key === 'ArrowLeft') nextImage();
							});
							
							// Close on click backdrop (away from control buttons & image)
							lightbox.addEventListener('click', function(e) {
								if (e.target === lightbox || e.target.closest('.w-full.h-full')) {
									closeLightbox();
								}
							});
						});
						</script>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<!-- Tags & Shortlink -->
			<div class="mt-10 flex flex-col md:flex-row gap-4 pt-6 mb-8 print:hidden">
				<!-- Tags Box -->
				<?php
				$tags = get_the_tags();
				if ( $tags ) :
					?>
					<div class="flex-1 bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700">
						<div class="flex items-center gap-2 mb-3 text-sm font-bold text-slate-900 dark:text-white">
							<i data-lucide="tags" class="w-4 h-4 text-primary" aria-hidden="true"></i> <?php esc_html_e( 'برچسب‌ها', 'eghtesadran' ); ?>
						</div>
						<div class="flex flex-wrap gap-2">
							<?php foreach ( $tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 hover:bg-primary hover:text-white dark:hover:bg-primary transition-colors px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-400"># <?php echo esc_html( $tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Shortlink Box -->
				<div class="w-full md:w-64 shrink-0 bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700">
					<div class="flex items-center gap-2 mb-3 text-sm font-bold text-slate-900 dark:text-white">
						<i data-lucide="link" class="w-4 h-4 text-primary" aria-hidden="true"></i> <?php esc_html_e( 'لینک کوتاه', 'eghtesadran' ); ?>
					</div>
					<div class="flex items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg p-1">
						<input type="text" id="shortlink-input" readonly value="<?php echo esc_url( wp_get_shortlink() ); ?>" class="bg-transparent w-full px-2 text-xs font-mono text-slate-500 dark:text-slate-400 outline-none text-left" dir="ltr">
						<button id="copy-shortlink-btn" class="bg-slate-50 dark:bg-slate-700 hover:bg-primary hover:text-white dark:hover:bg-primary border border-slate-200 dark:border-slate-600 p-1.5 rounded-md text-slate-600 dark:text-slate-300 transition-colors" title="<?php esc_attr_e( 'کپی لینک', 'eghtesadran' ); ?>">
							<i data-lucide="copy" class="w-3.5 h-3.5" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>



			<!-- Related Posts -->
			<?php
			$related_count      = get_theme_mod( 'eghtesadran_related_posts_count', 2 );
			$related_query_type = get_theme_mod( 'eghtesadran_related_posts_query_type', 'category' );

			$related_args = array(
				'post__not_in'        => array( $post_id ),
				'posts_per_page'      => $related_count,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
			);

			if ( 'tag' === $related_query_type ) {
				$tag_ids = wp_get_post_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				if ( ! empty( $tag_ids ) ) {
					$related_args['tag__in'] = $tag_ids;
				} else {
					$related_args['post__in'] = array( 0 );
				}
			} elseif ( 'both' === $related_query_type ) {
				$categories = wp_get_post_categories( $post_id );
				$tag_ids    = wp_get_post_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				
				$tax_query = array( 'relation' => 'OR' );
				
				if ( ! empty( $categories ) ) {
					$tax_query[] = array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $categories,
					);
				}
				if ( ! empty( $tag_ids ) ) {
					$tax_query[] = array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $tag_ids,
					);
				}
				
				if ( count( $tax_query ) > 1 ) {
					$related_args['tax_query'] = $tax_query;
				} else {
					$related_args['post__in'] = array( 0 );
				}
			} else {
				// Default category
				$categories = wp_get_post_categories( $post_id );
				if ( ! empty( $categories ) ) {
					$related_args['category__in'] = $categories;
				} else {
					$related_args['post__in'] = array( 0 );
				}
			}

			$related_query = new WP_Query( $related_args );

			if ( $related_query->have_posts() ) :
				?>
				<div class="mb-8 print:hidden">
					<div class="flex items-center gap-2 mb-5">
						<i data-lucide="layers" class="w-5 h-5 text-primary" aria-hidden="true"></i>
						<h3 class="text-lg font-black text-slate-900 dark:text-white"><?php esc_html_e( 'مطالب مرتبط', 'eghtesadran' ); ?></h3>
					</div>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
							<a href="<?php the_permalink(); ?>" class="group flex gap-4 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-primary dark:hover:border-primary transition-colors">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( array( 96, 96 ), array( 'class' => 'w-24 h-24 rounded-xl object-cover shrink-0 group-hover:scale-105 transition-transform' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/placeholder.jpg' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-24 h-24 rounded-xl object-cover shrink-0 group-hover:scale-105 transition-transform">
								<?php endif; ?>
								<div class="flex flex-col justify-center">
									<?php
									$primary_cat = eghtesadran_get_primary_category();
									if ( $primary_cat ) :
										?>
										<span class="text-[10px] font-bold text-primary mb-1"><?php echo esc_html( $primary_cat->name ); ?></span>
									<?php endif; ?>
									<h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-relaxed group-hover:text-primary dark:group-hover:text-red-400 transition-colors">
										<?php the_title(); ?>
									</h4>
								</div>
							</a>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			<?php endif; ?>
		</article>

		<?php
	endwhile;

	// Comments Section (Outside the loop, inside the column)
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	?>

	</div>
