<!-- SEARCH PAGE LAYOUT (8+4 Grid) -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- Search List Content (8 cols) -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Search Header Box -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 md:p-8 mb-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary transition-colors flex items-center gap-1">
                    <i data-lucide="home" class="w-3.5 h-3.5"></i> صفحه اصلی
                </a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-300">جستجو</span>
            </nav>
            
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white">
                        نتایج جستجو برای: <span class="text-primary">"<?php echo get_search_query(); ?>"</span>
                    </h1>
                </div>
            </div>
        </div>

        <!-- Search List -->
        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content/content', 'card' );
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8 print:hidden">
                <?php
                the_posts_pagination(
                    array(
                        'prev_text' => '<i data-lucide="chevron-right" class="w-5 h-5"></i>',
                        'next_text' => '<i data-lucide="chevron-left" class="w-5 h-5"></i>',
                        'class'     => 'eghtesadran-pagination',
                        'mid_size'  => 2,
                    )
                );
                ?>
            </div>
        <?php else : ?>
            <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                <i data-lucide="search-x" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                <p class="text-lg mb-4">متاسفانه نتیجه‌ای برای جستجوی شما یافت نشد.</p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar (4 cols) -->
    <div class="lg:col-span-4">
        <?php get_sidebar( 'single' ); ?>
    </div>
</section>
