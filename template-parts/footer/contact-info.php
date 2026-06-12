<?php
/**
 * Footer contact block.
 *
 * @package Eghtesadran
 */

$contact_details = eghtesadran_get_contact_details();
?>
<div class="space-y-2 sm:space-y-4 border-b border-white/5 sm:border-0 pb-3 sm:pb-0">
	<h3 class="footer-accordion-trigger flex justify-between items-center text-sm font-extrabold text-white relative w-full sm:inline-block cursor-pointer sm:cursor-auto select-none">
		<span><?php echo esc_html( get_theme_mod( 'eghtesadran_footer_contact_title', __( 'ارتباط با تحریریه', 'eghtesadran' ) ) ); ?></span>
		<span class="hidden sm:block absolute -bottom-1.5 right-0 w-6 h-0.5 bg-primary rounded-full"></span>
		<i data-lucide="chevron-down" class="footer-accordion-icon sm:hidden w-4 h-4 text-slate-500 transition-transform" aria-hidden="true"></i>
	</h3>
	<ul class="footer-accordion-content hidden sm:block space-y-3.5 text-xs text-slate-400 pt-2">
		<?php if ( ! empty( $contact_details['address'] ) ) : ?>
			<li class="flex items-start gap-2">
				<i data-lucide="map-pin" class="text-slate-550 shrink-0 mt-0.5 w-4 h-4" aria-hidden="true"></i>
				<span class="leading-relaxed text-[11px]"><?php echo esc_html( $contact_details['address'] ); ?></span>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $contact_details['phone'] ) ) : ?>
			<li class="flex items-center gap-2">
				<i data-lucide="phone" class="text-slate-550 shrink-0 w-4 h-4" aria-hidden="true"></i>
				<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $contact_details['phone'] ) ); ?>" dir="ltr" class="font-mono text-[11px] hover:text-white transition-colors">
					<?php echo esc_html( $contact_details['phone'] ); ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $contact_details['email'] ) ) : ?>
			<li class="flex items-center gap-2">
				<i data-lucide="mail" class="text-slate-550 shrink-0 w-4 h-4" aria-hidden="true"></i>
				<a href="<?php echo esc_url( 'mailto:' . antispambot( $contact_details['email'] ) ); ?>" class="font-mono text-[11px] hover:text-white transition-colors">
					<?php echo esc_html( antispambot( $contact_details['email'] ) ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</div>
