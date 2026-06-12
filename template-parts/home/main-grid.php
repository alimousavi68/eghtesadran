<!-- 3. THREE-COLUMN LAYOUT -->
<section class="grid grid-cols-1 md:grid-cols-12 lg:grid-cols-12 gap-6 items-start">
    <?php get_template_part( 'template-parts/home/category-sections' ); ?>
    <?php get_sidebar( 'home' ); ?>
    <?php get_template_part( 'template-parts/sidebar/ad-rail' ); ?>
</section>
