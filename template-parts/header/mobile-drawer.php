<?php
/**
 * Mobile drawer template.
 *
 * @package Eghtesadran
 */
?>
<div id="mobile-drawer" class="fixed inset-0 z-[100] flex pointer-events-none print:hidden" dir="rtl">
	<div id="menu-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-200 pointer-events-none"></div>

	<div id="menu-drawer-panel" class="relative w-80 max-w-full bg-white dark:bg-slate-800 h-full shadow-2xl flex flex-col translate-x-full transition-transform duration-200 ease-[cubic-bezier(0.4,0,0.2,1)] pointer-events-auto">
		<div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
			<div class="flex flex-col">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php echo wp_kses_post( eghtesadran_get_site_logo( 'h-12 w-auto' ) ); ?>
				</a>
				<div class="flex h-[3px] mt-1 w-16">
					<div class="w-1/3 bg-primary"></div>
					<div class="w-2/3 bg-slate-300 dark:bg-slate-600"></div>
				</div>
			</div>
			<button id="close-menu-btn" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 rounded-full transition-colors font-bold text-lg" aria-label="<?php echo esc_attr__( 'بستن منوی موبایل', 'eghtesadran' ); ?>">×</button>
		</div>

		<div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
			<nav aria-label="<?php echo esc_attr__( 'Mobile menu', 'eghtesadran' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'container'      => false,
						'depth'          => 2,
						'menu_class'     => 'flex flex-col gap-1 list-none',
						'fallback_cb'    => 'eghtesadran_mobile_menu_fallback',
						'walker'         => new Eghtesadran_Mobile_Nav_Walker(),
					)
				);
				?>
			</nav>

			<div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-700">
				<button class="theme-toggle-btn w-full flex items-center justify-between py-2.5 px-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" aria-label="<?php echo esc_attr__( 'تغییر حالت رنگ', 'eghtesadran' ); ?>">
					<span class="text-slate-800 dark:text-slate-200 text-sm font-medium"><?php esc_html_e( 'حالت شب / روز', 'eghtesadran' ); ?></span>
					<i class="theme-toggle-icon w-5 h-5 text-slate-600 dark:text-slate-400" data-lucide="moon" stroke-width="1.5" aria-hidden="true"></i>
				</button>
			</div>
		</div>
	</div>
</div>
