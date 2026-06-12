<?php
/**
 * Header middle layer template.
 *
 * @package Eghtesadran
 */
?>
<div class="border-b border-slate-100 dark:border-slate-700 py-0 h-[81px] flex items-center">
	<div class="max-w-[1400px] mx-auto w-full flex flex-row justify-between items-center px-4 md:px-8 h-full">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center shrink-0" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php echo wp_kses_post( eghtesadran_get_site_logo( 'h-[50px] md:h-[65px] w-auto py-1' ) ); ?>
		</a>

		<!-- Left Side: Ad Slot -->
		<div class="hidden md:flex w-full max-w-[600px] h-[60px] items-center justify-center">
			<?php if ( is_active_sidebar( 'sidebar-header-ad' ) ) : ?>
				<?php dynamic_sidebar( 'sidebar-header-ad' ); ?>
			<?php endif; ?>
		</div>

		<div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-400">
			<button class="open-search-btn p-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors" aria-label="<?php echo esc_attr__( 'باز کردن جستجو', 'eghtesadran' ); ?>">
				<i data-lucide="search" class="w-[22px] h-[22px]" stroke-width="1.5" aria-hidden="true"></i>
			</button>
			<button class="theme-toggle-btn p-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors" aria-label="<?php echo esc_attr__( 'تغییر حالت رنگ', 'eghtesadran' ); ?>">
				<i class="theme-toggle-icon w-[22px] h-[22px]" data-lucide="moon" stroke-width="1.5" aria-hidden="true"></i>
			</button>
			<button class="open-menu-btn p-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-primary rounded-full transition-colors lg:hidden" aria-label="<?php echo esc_attr__( 'باز کردن منوی موبایل', 'eghtesadran' ); ?>">
				<i data-lucide="menu" class="w-[22px] h-[22px]" stroke-width="1.5" aria-hidden="true"></i>
			</button>
		</div>
	</div>
</div>
