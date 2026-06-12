<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Dark Mode Init -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <?php wp_head(); ?>
</head>

<body
    <?php body_class( 'bg-[#f4f5f7] dark:bg-slate-900 text-[#0f172a] dark:text-white font-sans antialiased selection:bg-red-900 selection:text-white transition-colors duration-200' ); ?>>
    <?php wp_body_open(); ?>
    <div class="flex flex-col min-h-screen">

                <?php get_template_part( 'template-parts/header/search-modal' ); ?>
        <?php get_template_part( 'template-parts/header/mobile-drawer' ); ?>
        <?php get_template_part( 'template-parts/header/sticky-header' ); ?>

        <!-- 1. HEADER (Static) -->
        <header class="relative z-40 flex flex-col w-full shadow-sm bg-[#f4f5f7] dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 print:hidden">
            <?php get_template_part( 'template-parts/header/topbar' ); ?>
            <?php get_template_part( 'template-parts/header/middle-layer' ); ?>
            <?php get_template_part( 'template-parts/header/navigation' ); ?>
            <?php get_template_part( 'template-parts/header/ticker' ); ?>
        </header>

        <!-- Main Container -->
        <main class="flex-1 w-full max-w-[1400px] mx-auto px-4 md:px-8 py-6 space-y-6 md:space-y-8">
