<?php
/**
 * Homepage category sections template.
 *
 * @package Eghtesadran
 */
?>
<!-- Main content column -->
<div class="md:col-span-8 lg:col-span-8 space-y-8">

	<?php if ( is_active_sidebar( 'home-content' ) ) : ?>
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
			<?php dynamic_sidebar( 'home-content' ); ?>
		</div>
	<?php else : ?>
		<!-- Fallback static layout (kept for reference and backward compatibility) -->
		<!-- Category 1 & 5 Side-by-Side Wrapper -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
			<?php eghtesadran_render_category_section( 'bank-insurance', 'A' ); ?>
			<?php eghtesadran_render_category_section( 'energy', 'A' ); ?>
		</div>

		<?php eghtesadran_render_category_section( 'bours', 'B' ); ?>
		<?php eghtesadran_render_category_section( 'industry-mining', 'C' ); ?>
		<?php eghtesadran_render_category_section( 'gold-currency', 'B' ); ?>
		<?php eghtesadran_render_category_section( 'housing', 'B' ); ?>
		<?php eghtesadran_render_category_section( 'auto', 'C' ); ?>
	<?php endif; ?>

</div>
