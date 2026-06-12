<?php
/**
 * The sidebar containing the main widget area for the homepage.
 *
 * @package Eghtesadran
 */
?>
<!-- Sidebar column -->
<aside class="md:col-span-4 lg:col-span-3 space-y-8 shrink-0 w-full flex flex-col self-start sticky top-28">

	<?php if ( is_active_sidebar( 'sidebar-home' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-home' ); ?>
	<?php endif; ?>

</aside>
