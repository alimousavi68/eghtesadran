<!-- Single Sidebar (4 cols) -->
<aside class="lg:col-span-4 space-y-8 flex flex-col self-start sticky top-28 print:hidden">

	<?php if ( is_active_sidebar( 'sidebar-single' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-single' ); ?>
	<?php endif; ?>

</aside>
