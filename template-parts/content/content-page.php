<?php
/**
 * Template part for displaying page content.
 *
 * @package Eghtesadran
 */

while ( have_posts() ) :
	the_post();
	?>

	<!-- Main Article Content (Centered, 10 cols) -->
	<div class="lg:col-span-10 lg:col-start-2 space-y-6">
		<!-- Article Card -->
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 md:p-8 mb-8 article-container relative' ); ?>>

			<!-- Print Logo -->
			<div class="hidden print:block print-logo text-center mb-8">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<img src="<?php echo esc_url( eghtesadran_asset_uri( 'assets/images/logo.webp' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-16 mx-auto">
					<?php
				}
				?>
			</div>

			<!-- Breadcrumbs -->
			<nav class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-6 pb-4 border-b border-slate-100 dark:border-slate-700 print:hidden">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary transition-colors flex items-center gap-1">
					<i data-lucide="home" class="w-3.5 h-3.5" aria-hidden="true"></i> <?php esc_html_e( 'صفحه اصلی', 'eghtesadran' ); ?>
				</a>
				<span>/</span>
				<span class="text-slate-600 dark:text-slate-300"><?php the_title(); ?></span>
			</nav>

			<!-- Article Header -->
			<header class="mb-8">
				<h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white leading-[1.8] md:leading-loose">
					<?php the_title(); ?>
				</h1>
			</header>

			<!-- Featured Image -->
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="mb-8 rounded-2xl overflow-hidden shadow-sm">
					<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto object-cover aspect-[16/9]' ) ); ?>
				</figure>
			<?php endif; ?>

			<!-- Article Body -->
			<div class="article-content text-slate-800 dark:text-slate-200 text-sm md:text-base leading-loose text-justify space-y-6">
				<?php the_content(); ?>
			</div>
		</article>
	</div>
	<?php
endwhile;
