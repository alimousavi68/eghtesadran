<?php
/**
 * Footer brand block.
 *
 * @package Eghtesadran
 */

$social_links = eghtesadran_get_social_links();
?>
<div class="col-span-1 lg:col-span-5 space-y-5">
	<h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-2.5">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php echo wp_kses_post( eghtesadran_get_site_logo( 'h-[65px] w-auto' ) ); ?>
		</a>
	</h2>
	<p class="text-xs md:text-sm leading-7 text-slate-400 text-justify max-w-sm">
		<?php echo esc_html( eghtesadran_get_brand_text() ); ?>
	</p>

	<?php if ( ! empty( $social_links ) ) : ?>
		<div class="pt-2 flex items-center gap-2.5">
			<?php foreach ( $social_links as $social_link ) : ?>
				<a href="<?php echo esc_url( $social_link['url'] ); ?>" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-primary border border-white/10 hover:border-primary flex items-center justify-center text-slate-400 hover:text-white transition-all" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social_link['label'] ); ?>">
					<i data-lucide="<?php echo esc_attr( $social_link['icon'] ); ?>" class="w-4 h-4" aria-hidden="true"></i>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
