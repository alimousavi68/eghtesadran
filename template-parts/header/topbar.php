<?php
/**
 * Header topbar template.
 *
 * @package Eghtesadran
 */

$social_links = eghtesadran_get_social_links();
?>
<div class="bg-[#f4f5f7] dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs transition-colors font-medium">
	<div class="max-w-[1400px] mx-auto w-full flex justify-between items-center py-2 px-4 md:px-8">
		<div class="flex items-center gap-4 text-[10px] sm:text-[11px] font-bold">
			<div class="flex items-center gap-1.5">
				<i data-lucide="calendar" class="w-3.5 h-3.5" aria-hidden="true"></i>
				<span><?php echo esc_html( wp_date( 'Y F j l' ) ); ?></span>
			</div>
			<div class="hidden sm:flex items-center gap-1.5">
				<i data-lucide="clock" class="w-3.5 h-3.5" aria-hidden="true"></i>
				<span>
					<?php
					printf(
						/* translators: %s: current site time. */
						esc_html__( 'بروزرسانی: %s', 'eghtesadran' ),
						esc_html( wp_date( 'H:i' ) )
					);
					?>
				</span>
			</div>
		</div>

		<div class="flex items-center gap-3 text-[10px] sm:text-[11px] font-bold">
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="hidden md:block" aria-label="<?php echo esc_attr__( 'لینک‌های ثانویه', 'eghtesadran' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'depth'          => 1,
							'menu_class'     => 'flex items-center gap-3 list-none',
							'fallback_cb'    => false,
							'link_before'    => '<span class="hover:text-primary transition-colors">',
							'link_after'     => '</span>',
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( ! empty( $social_links ) ) : ?>
				<div class="hidden md:block w-px h-3 bg-slate-300 dark:bg-slate-600"></div>
				<div class="flex items-center gap-2.5">
					<?php foreach ( $social_links as $social_link ) : ?>
						<a href="<?php echo esc_url( $social_link['url'] ); ?>" class="hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social_link['label'] ); ?>">
							<i data-lucide="<?php echo esc_attr( $social_link['icon'] ); ?>" class="w-3.5 h-3.5" stroke-width="1.8" aria-hidden="true"></i>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
