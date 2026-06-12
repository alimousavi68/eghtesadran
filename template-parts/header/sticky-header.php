<?php
/**
 * Sticky header template.
 *
 * @package Eghtesadran
 */

$sticky_menu_items = eghtesadran_get_flat_menu_items( 'primary' );
?>
<header id="sticky-header" class="fixed top-0 left-0 right-0 z-[60] bg-[#f4f5f7] dark:bg-slate-800 text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 shadow-md transition-transform duration-300 -translate-y-full print:hidden">
	<div class="max-w-[1400px] mx-auto w-full px-4 md:px-8 py-3.5 flex items-center justify-between">
		<div class="flex items-center gap-6">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php echo wp_kses_post( eghtesadran_get_site_logo( 'h-[45px] w-auto' ) ); ?>
			</a>

			<nav class="hidden lg:flex items-center text-[13px] font-bold whitespace-nowrap gap-1" aria-label="<?php echo esc_attr__( 'منوی چسبان', 'eghtesadran' ); ?>">
				<?php foreach ( $sticky_menu_items as $index => $sticky_item ) : ?>
					<a href="<?php echo esc_url( $sticky_item['url'] ); ?>" class="px-3 py-1 <?php echo 0 === $index || ! empty( $sticky_item['current'] ) ? 'text-primary' : 'hover:text-primary'; ?> <?php echo 0 !== $index ? 'nav-divider-sticky h-4 flex items-center' : ''; ?>">
						<?php echo esc_html( $sticky_item['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>

		<div class="flex items-center gap-2">
			<button class="open-search-btn p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors" aria-label="<?php echo esc_attr__( 'باز کردن جستجو', 'eghtesadran' ); ?>">
				<i data-lucide="search" class="w-5 h-5" aria-hidden="true"></i>
			</button>
			<button class="theme-toggle-btn p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors" aria-label="<?php echo esc_attr__( 'تغییر حالت رنگ', 'eghtesadran' ); ?>">
				<i class="theme-toggle-icon w-5 h-5" data-lucide="moon" aria-hidden="true"></i>
			</button>
			<button class="open-menu-btn p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors lg:hidden" aria-label="<?php echo esc_attr__( 'باز کردن منوی موبایل', 'eghtesadran' ); ?>">
				<i data-lucide="menu" class="w-5 h-5" aria-hidden="true"></i>
			</button>
		</div>
	</div>
</header>
