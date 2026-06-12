<?php
/**
 * Footer legal block.
 *
 * @package Eghtesadran
 */
?>
<div class="space-y-4">
	<h3 class="text-sm font-extrabold text-white relative inline-block">
		<?php echo esc_html( get_theme_mod( 'eghtesadran_footer_licenses_title', __( 'مجوزهای رسانه', 'eghtesadran' ) ) ); ?>
		<span class="absolute -bottom-1.5 right-0 w-6 h-0.5 bg-primary rounded-full"></span>
	</h3>
	<div class="flex gap-2.5 pt-2">
		<div class="bg-white/5 border border-white/10 rounded-xl p-2.5 flex items-center justify-center w-20 h-20 group">
			<div class="text-center">
				<div class="text-white group-hover:text-primary font-bold text-2xl transition-colors leading-none">e</div>
				<div class="text-[8px] text-slate-400 mt-2 opacity-80"><?php esc_html_e( 'ساماندهی', 'eghtesadran' ); ?></div>
			</div>
		</div>
		<div class="bg-white/5 border border-white/10 rounded-xl p-2.5 flex items-center justify-center w-20 h-20 group">
			<div class="text-center flex flex-col items-center justify-center">
				<i data-lucide="shield-check" class="text-white group-hover:text-primary transition-colors w-6 h-6" stroke-width="1.5" aria-hidden="true"></i>
				<div class="text-[8px] text-slate-400 mt-2 opacity-80"><?php esc_html_e( 'عضویت', 'eghtesadran' ); ?></div>
			</div>
		</div>
	</div>
</div>
