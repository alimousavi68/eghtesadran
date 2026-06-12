<?php
/**
 * Header navigation template.
 *
 * @package Eghtesadran
 */
?>
<div class="bg-primary text-white w-full hidden md:flex flex-row shadow-md border-t border-red-700 h-[38px]">
	<div class="max-w-[1400px] mx-auto w-full px-4 md:px-8 h-full">
		<nav class="h-full" aria-label="<?php echo esc_attr__( 'Primary menu', 'eghtesadran' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'depth'          => 2,
					'menu_class'     => 'flex w-full text-[13px] lg:text-sm font-bold items-center h-full list-none',
					'fallback_cb'    => 'eghtesadran_primary_menu_fallback',
					'walker'         => new Eghtesadran_Primary_Nav_Walker(),
				)
			);
			?>
		</nav>
	</div>
</div>
