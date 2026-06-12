<?php
/**
 * Footer quick links block.
 *
 * @package Eghtesadran
 */
?>
<div class="space-y-2 sm:space-y-4 border-b border-white/5 sm:border-0 pb-3 sm:pb-0">
	<h3 class="footer-accordion-trigger flex justify-between items-center text-sm font-extrabold text-white relative w-full sm:inline-block cursor-pointer sm:cursor-auto select-none">
		<span><?php echo esc_html( get_theme_mod( 'eghtesadran_footer_quick_links_title', __( 'دسترسی سریع', 'eghtesadran' ) ) ); ?></span>
		<span class="hidden sm:block absolute -bottom-1.5 right-0 w-6 h-0.5 bg-primary rounded-full"></span>
		<i data-lucide="chevron-down" class="footer-accordion-icon sm:hidden w-4 h-4 text-slate-500 transition-transform" aria-hidden="true"></i>
	</h3>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'footer',
			'container'      => false,
			'depth'          => 1,
			'menu_class'     => 'footer-accordion-content hidden sm:block space-y-2.5 font-bold text-xs text-slate-400 pt-2 list-none',
			'fallback_cb'    => 'eghtesadran_footer_menu_fallback',
			'link_before'    => '<span class="flex items-center gap-1.5"><i data-lucide="chevron-left" class="w-3.5 h-3.5 text-slate-650 shrink-0" aria-hidden="true"></i><span>',
			'link_after'     => '</span></span>',
		)
	);
	?>
</div>
