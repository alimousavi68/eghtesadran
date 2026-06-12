<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Eghtesadran
 */

get_header();
?>

<main class="flex-1 w-full max-w-[1400px] mx-auto px-4 md:px-8 py-6 space-y-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-6 shadow-sm border border-slate-100 dark:border-slate-700">
        <header class="mb-6">
            <h1 class="text-xl md:text-2xl font-black text-slate-800 dark:text-white relative inline-block pr-4">
                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-primary rounded-full"></span>
                <?php single_post_title(); ?>
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content/content', 'card' );
                endwhile;
                ?>
            </div>
            
            <div class="mt-8">
                <?php
                the_posts_pagination(
                    array(
                        'prev_text' => '<i data-lucide="chevron-right" class="w-5 h-5"></i>',
                        'next_text' => '<i data-lucide="chevron-left" class="w-5 h-5"></i>',
                        'class'     => 'pagination flex items-center justify-center gap-2',
                    )
                );
                ?>
            </div>

        <?php else : ?>
            <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                <i data-lucide="search-x" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                <p class="text-lg">متاسفانه مطلبی یافت نشد.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
