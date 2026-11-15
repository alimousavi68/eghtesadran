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
