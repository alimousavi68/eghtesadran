<?php
/**
 * Performance helpers: transient caching infrastructure.
 *
 * @package Eghtesadran
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Category IDs excluded from "related posts" queries because they are
 * too broad (e.g. "اخبار" or "اقتصادی" with 14-19 k posts each).
 *
 * Override with:
 *   define( 'EGHTESADRAN_RELATED_EXCLUDED_CATEGORIES', array( 1, 133 ) );
 * in wp-config.php, or use the filter below.
 */
if ( ! defined( 'EGHTESADRAN_RELATED_EXCLUDED_CATEGORIES' ) ) {
	define( 'EGHTESADRAN_RELATED_EXCLUDED_CATEGORIES', array( 1, 133 ) );
}

/**
 * Returns the current cache version number.
 *
 * Every widget / ticker / category-section transient key should include this
 * value so that publishing or updating a post instantly invalidates all of
 * them without scanning the transient table.
 *
 * @return int
 */
function eghtesadran_get_cache_ver() {
	return (int) get_option( 'esr_cache_ver', 0 );
}

/**
 * Bumps the global cache version by one.
 *
 * Hooked into transition_post_status (publish ↔ any) and deleted_post.
 *
 * @return void
 */
function eghtesadran_bump_cache_ver() {
	$current = eghtesadran_get_cache_ver();
	update_option( 'esr_cache_ver', $current + 1, true ); // autoloaded
}

/**
 * Bump cache version when a post is published, unpublished, or updated.
 *
 * @param string $new_status New post status.
 * @param string $old_status Old post status.
 * @return void
 */
function eghtesadran_on_post_transition( $new_status, $old_status ) {
	if ( 'publish' === $new_status || 'publish' === $old_status ) {
		eghtesadran_bump_cache_ver();
	}
}
add_action( 'transition_post_status', 'eghtesadran_on_post_transition', 10, 2 );

/**
 * Bump cache version when a post is permanently deleted.
 *
 * @return void
 */
add_action( 'deleted_post', 'eghtesadran_bump_cache_ver' );

/**
 * Builds a transient cache key for a widget query.
 *
 * The key is derived from the widget's settings (type, category, count,
 * offset, order, post_type) plus the global cache version. The current
 * post ID is deliberately excluded so the same cache is reused across
 * different single pages.
 *
 * @param string $prefix Short prefix, e.g. 'esr_wup' or 'esr_wmp'.
 * @param array  $parts  Associative array of key components.
 * @return string Transient key (≤ 172 chars).
 */
function eghtesadran_widget_cache_key( $prefix, $parts ) {
	$parts['v'] = eghtesadran_get_cache_ver();
	$hash       = substr( md5( wp_json_encode( $parts ) ), 0, 12 );
	return $prefix . '_' . $hash;
}

/**
 * Safeguards search queries against database table-scan overload.
 *
 * For empty or < 3 character search terms, forces an empty result set
 * and disables found_rows calculation, preventing massive LIKE queries
 * across the wp_posts table.
 *
 * @param WP_Query $query The current WP_Query instance.
 * @return void
 */
function eghtesadran_safeguard_search_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$search = trim( (string) $query->get( 's' ) );
		if ( mb_strlen( $search ) < 3 ) {
			$query->set( 'post__in', array( 0 ) );
			$query->set( 'no_found_rows', true );
		}
	}
}
add_action( 'pre_get_posts', 'eghtesadran_safeguard_search_query' );
