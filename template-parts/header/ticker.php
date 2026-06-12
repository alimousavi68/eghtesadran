<?php
/**
 * Header ticker template.
 *
 * @package Eghtesadran
 */

$ticker_items = eghtesadran_get_ticker_items( 5 );
?>
<div class="border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/40 py-2 overflow-hidden flex items-center">
	<div class="max-w-[1400px] mx-auto w-full px-4 md:px-8 flex items-center w-full">
		<div class="flex items-center gap-1.5 shrink-0 bg-primary text-white text-xs font-black px-3 py-1.5 rounded-lg ml-4 shadow-sm shadow-red-500/10 z-10 relative w-fit whitespace-nowrap">
			<span class="flex h-2 w-2 relative">
				<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-300 opacity-75"></span>
				<span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
			</span>
			<span><?php echo esc_html( get_theme_mod( 'eghtesadran_header_ticker_label', __( 'اخبار فوری', 'eghtesadran' ) ) ); ?></span>
		</div>

		<div class="ticker-wrap flex-1" dir="ltr">
			<div class="ticker-content flex items-center gap-16 text-xs font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap animate-[ticker-marquee-left_40s_linear_infinite]">
				<?php for ( $set_index = 0; $set_index < 2; $set_index++ ) : ?>
					<div class="flex items-center gap-16 shrink-0" dir="rtl">
						<?php foreach ( $ticker_items as $ticker_item ) : ?>
							<a href="<?php echo esc_url( $ticker_item['url'] ); ?>" class="hover:text-primary transition-colors flex items-center gap-2">
								<span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
								<?php echo esc_html( $ticker_item['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</div>
