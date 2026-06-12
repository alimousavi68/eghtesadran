<?php
/**
 * Ad rail sidebar template.
 *
 * @package Eghtesadran
 */
?>
<div class="lg:col-span-1 hidden lg:block self-start sticky top-28 w-full space-y-4">
	<?php if ( is_active_sidebar( 'sidebar-ad-rail' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-ad-rail' ); ?>
	<?php endif; ?>
</div>
