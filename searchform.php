<?php
/**
 * Search form template.
 *
 * @package Eghtesadran
 */

?>
<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="flex items-center p-2">
	<label class="sr-only" for="search-input"><?php esc_html_e( 'جستجو برای:', 'eghtesadran' ); ?></label>
	<i data-lucide="search" class="w-6 h-6 text-slate-400 dark:text-slate-500 ml-3 shrink-0" aria-hidden="true"></i>
	<input
		id="search-input"
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php echo esc_attr__( 'جستجو در اخبار اقتصادی، بازارها و تحلیل‌ها...', 'eghtesadran' ); ?>"
		class="w-full py-4 px-2 text-lg outline-none text-slate-700 dark:text-slate-300 bg-transparent placeholder-slate-400"
	/>
	<button
		id="close-search-btn"
		type="button"
		class="ml-2 w-10 h-10 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl transition-colors font-bold text-lg"
		aria-label="<?php echo esc_attr__( 'بستن جستجو', 'eghtesadran' ); ?>"
	>×</button>
	<button type="submit" class="bg-primary hover:bg-primary-hover text-white font-bold px-6 py-3 rounded-xl transition-colors shrink-0">
		<?php esc_html_e( 'جستجو', 'eghtesadran' ); ?>
	</button>
</form>
