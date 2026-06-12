<?php

$header_content = file_get_contents('header.php');

// search-modal.php
$search_start = strpos($header_content, '<!-- Search Modal -->');
$search_end = strpos($header_content, '<!-- Side Drawer Menu (Mobile) -->');
$search_modal = substr($header_content, $search_start, $search_end - $search_start);
file_put_contents('template-parts/header/search-modal.php', $search_modal);

// mobile-drawer.php
$drawer_start = $search_end;
$drawer_end = strpos($header_content, '<!-- Minimal Sticky Header -->');
$mobile_drawer = substr($header_content, $drawer_start, $drawer_end - $drawer_start);
file_put_contents('template-parts/header/mobile-drawer.php', $mobile_drawer);

// sticky-header.php
$sticky_start = $drawer_end;
$sticky_end = strpos($header_content, '<!-- 1. HEADER (Static) -->');
$sticky_header = substr($header_content, $sticky_start, $sticky_end - $sticky_start);
file_put_contents('template-parts/header/sticky-header.php', $sticky_header);

// Now for the static header parts inside <!-- 1. HEADER (Static) -->
$static_header_start = $sticky_end;
$static_header_end = strpos($header_content, '<!-- Main Container -->');

$top_layer_start = strpos($header_content, '<!-- Top Layer -->');
$top_layer_end = strpos($header_content, '<!-- Middle Layer -->');
$top_layer = substr($header_content, $top_layer_start, $top_layer_end - $top_layer_start);
file_put_contents('template-parts/header/topbar.php', $top_layer);

$mid_layer_start = $top_layer_end;
$mid_layer_end = strpos($header_content, '<!-- Bottom Layer - Navigation -->');
$mid_layer = substr($header_content, $mid_layer_start, $mid_layer_end - $mid_layer_start);
file_put_contents('template-parts/header/middle-layer.php', $mid_layer);

$nav_layer_start = $mid_layer_end;
$nav_layer_end = strpos($header_content, '<!-- News Ticker');
$nav_layer = substr($header_content, $nav_layer_start, $nav_layer_end - $nav_layer_start);
file_put_contents('template-parts/header/navigation.php', $nav_layer);

$ticker_start = $nav_layer_end;
$ticker_end = strpos($header_content, '</header>', $ticker_start);
$ticker = substr($header_content, $ticker_start, $ticker_end - $ticker_start);
file_put_contents('template-parts/header/ticker.php', $ticker);

// Now rewrite header.php
$new_header_content = substr($header_content, 0, $search_start);
$new_header_content .= "        <?php get_template_part( 'template-parts/header/search-modal' ); ?>\n";
$new_header_content .= "        <?php get_template_part( 'template-parts/header/mobile-drawer' ); ?>\n";
$new_header_content .= "        <?php get_template_part( 'template-parts/header/sticky-header' ); ?>\n\n";
$new_header_content .= "        <!-- 1. HEADER (Static) -->\n";
$new_header_content .= "        <header class=\"relative z-40 flex flex-col w-full shadow-sm bg-[#f4f5f7] dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 print:hidden\">\n";
$new_header_content .= "            <?php get_template_part( 'template-parts/header/topbar' ); ?>\n";
$new_header_content .= "            <?php get_template_part( 'template-parts/header/middle-layer' ); ?>\n";
$new_header_content .= "            <?php get_template_part( 'template-parts/header/navigation' ); ?>\n";
$new_header_content .= "            <?php get_template_part( 'template-parts/header/ticker' ); ?>\n";
$new_header_content .= "        </header>\n\n";
$new_header_content .= "        <!-- Main Container -->\n";
$new_header_content .= "        <main class=\"flex-1 w-full max-w-[1400px] mx-auto px-4 md:px-8 py-6 space-y-6 md:space-y-8\">\n";

file_put_contents('header.php', $new_header_content);


// Now for footer.php
$footer_content = file_get_contents('footer.php');

$brand_start = strpos($footer_content, '<!-- Column 1: Branding -->');
$brand_end = strpos($footer_content, '<!-- Column 2: Quick Links -->');
$brand = substr($footer_content, $brand_start, $brand_end - $brand_start);
file_put_contents('template-parts/footer/brand.php', $brand);

$quick_links_start = $brand_end;
$quick_links_end = strpos($footer_content, '<!-- Column 3: Contact & Newsletter -->');
$quick_links = substr($footer_content, $quick_links_start, $quick_links_end - $quick_links_start);
file_put_contents('template-parts/footer/quick-links.php', $quick_links);

$contact_start = $quick_links_end;
$contact_end = strpos($footer_content, '<!-- Column 4: Enamad & Licenses -->');
if ($contact_end === false) {
    $contact_end = strpos($footer_content, '</div>', $contact_start);
}
// Actually, since I hid column 4, let's find the end of the grid.
$grid_end = strpos($footer_content, '<!-- Bottom Footer -->');

$contact_and_licenses = substr($footer_content, $contact_start, $grid_end - $contact_start);
// I'll extract Column 4
$col4_start = strpos($contact_and_licenses, '<!-- Column 4: Enamad & Licenses -->');
if($col4_start !== false) {
    $contact = substr($contact_and_licenses, 0, $col4_start);
    $licenses = substr($contact_and_licenses, $col4_start);
    file_put_contents('template-parts/footer/contact-info.php', $contact);
    file_put_contents('template-parts/footer/legal.php', $licenses);
} else {
    file_put_contents('template-parts/footer/contact-info.php', $contact_and_licenses);
    file_put_contents('template-parts/footer/legal.php', "<!-- No Legal col -->");
}

$new_footer = substr($footer_content, 0, $brand_start);
$new_footer .= "                <?php get_template_part( 'template-parts/footer/brand' ); ?>\n";
$new_footer .= "                <?php get_template_part( 'template-parts/footer/quick-links' ); ?>\n";
$new_footer .= "                <?php get_template_part( 'template-parts/footer/contact-info' ); ?>\n";
$new_footer .= "                <?php get_template_part( 'template-parts/footer/legal' ); ?>\n";
$new_footer .= substr($footer_content, $grid_end);

file_put_contents('footer.php', $new_footer);

echo "Done splitting header and footer.\n";
