<?php
/**
 * Search modal template.
 *
 * @package Eghtesadran
 */
?>
<div id="search-modal" class="fixed inset-0 z-[110] flex hidden items-start justify-center pt-20 px-4">
	<div id="search-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
	<div class="relative bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in slide-in-from-top-10 duration-300 border border-slate-100 dark:border-slate-700">
		<?php get_search_form(); ?>
	</div>
</div>
