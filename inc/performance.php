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
 * For empty, < 3, or > 50 character search terms, forces an empty result set
 * and disables found_rows calculation, preventing massive LIKE queries
 * across the wp_posts table.
 *
 * @param WP_Query $query The current WP_Query instance.
 * @return void
 */
function eghtesadran_safeguard_search_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$search = trim( (string) $query->get( 's' ) );
		$len    = mb_strlen( $search );
		if ( $len < 3 || $len > 50 ) {
			$query->set( 'post__in', array( 0 ) );
			$query->set( 'no_found_rows', true );
		}
	}
}
add_action( 'pre_get_posts', 'eghtesadran_safeguard_search_query' );

/**
 * Removes the redundant GROUP BY clause on single-term category and tag archive queries.
 *
 * WordPress core defaults to grouping by wp_posts.ID whenever taxonomy joins occur.
 * For single-term archives, each post has at most one relationship entry, so rows are
 * inherently unique. Removing GROUP BY eliminates MySQL's "Using temporary; Using filesort".
 *
 * @param string   $groupby The GROUP BY clause.
 * @param WP_Query $query   The current WP_Query instance.
 * @return string
 */
function eghtesadran_optimize_archive_groupby( $groupby, $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( $query->is_category() || $query->is_tag() ) ) {
		$is_single = false;

		if ( $query->is_category() ) {
			$cat_name = (string) $query->get( 'category_name' );
			$cat_id   = $query->get( 'cat' );
			if ( ! empty( $cat_id ) && is_numeric( $cat_id ) && (int) $cat_id > 0 ) {
				$is_single = true;
			} elseif ( ! empty( $cat_name ) && strpos( $cat_name, ',' ) === false && strpos( $cat_name, '+' ) === false ) {
				$is_single = true;
			}
		} elseif ( $query->is_tag() ) {
			$tag_slug = (string) $query->get( 'tag' );
			$tag_id   = $query->get( 'tag_id' );
			if ( ! empty( $tag_id ) && is_numeric( $tag_id ) && (int) $tag_id > 0 ) {
				$is_single = true;
			} elseif ( ! empty( $tag_slug ) && strpos( $tag_slug, ',' ) === false && strpos( $tag_slug, '+' ) === false ) {
				$is_single = true;
			}
		}

		if ( $is_single ) {
			return '';
		}
	}

	return $groupby;
}
add_filter( 'posts_groupby', 'eghtesadran_optimize_archive_groupby', 10, 2 );

/**
 * Caps deep pagination on archive and search requests to thwart scraper bots.
 *
 * Requests exceeding the maximum page threshold are served a 404 response
 * without executing expensive deep-offset database scans.
 *
 * @param WP_Query $query The current WP_Query instance.
 * @return void
 */
function eghtesadran_limit_pagination_depth( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$paged = max( 1, (int) $query->get( 'paged' ), (int) $query->get( 'page' ) );

	// Archive pages cap (default 50)
	if ( $query->is_archive() ) {
		$max_archive = (int) apply_filters( 'eghtesadran_max_archive_paged', 50 );
		if ( $paged > $max_archive ) {
			$query->set_404();
			status_header( 404 );
			nocache_headers();
			$query->set( 'post__in', array( 0 ) );
			$query->set( 'no_found_rows', true );
			return;
		}
	}

	// Search pages cap (default 10)
	if ( $query->is_search() ) {
		$max_search = (int) apply_filters( 'eghtesadran_max_search_paged', 10 );
		if ( $paged > $max_search ) {
			$query->set_404();
			status_header( 404 );
			nocache_headers();
			$query->set( 'post__in', array( 0 ) );
			$query->set( 'no_found_rows', true );
			return;
		}
	}

	// Set no_found_rows where pagination is unneeded (e.g., feeds)
	if ( $query->is_feed() ) {
		$query->set( 'no_found_rows', true );
	}
}
add_action( 'pre_get_posts', 'eghtesadran_limit_pagination_depth', 5 );

/**
 * Short-circuits and caches frontend search queries using transients.
 *
 * Checks if search results for the given term, page, and cache version exist.
 * If cached, bypasses SQL generation and table scans entirely.
 * If not cached, hooks into posts_results to cache post IDs and pagination metadata.
 *
 * @param array|null $posts Pre-query posts array (null by default).
 * @param WP_Query   $query The current WP_Query instance.
 * @return array|null
 */
function eghtesadran_cache_search_pre_query( $posts, $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return $posts;
	}

	$search = trim( (string) $query->get( 's' ) );
	$len    = mb_strlen( $search );
	if ( $len < 3 || $len > 50 ) {
		$query->found_posts   = 0;
		$query->max_num_pages = 0;
		return array();
	}

	$paged = max( 1, (int) $query->get( 'paged' ), (int) $query->get( 'page' ) );

	$cache_key = 'esr_s_' . substr( md5( $search ), 0, 16 ) . '_p' . $paged . '_v' . eghtesadran_get_cache_ver();
	$cached    = get_transient( $cache_key );

	if ( false !== $cached && is_array( $cached ) && isset( $cached['ids'], $cached['found_posts'], $cached['max_num_pages'] ) ) {
		$query->found_posts   = (int) $cached['found_posts'];
		$query->max_num_pages = (int) $cached['max_num_pages'];

		if ( empty( $cached['ids'] ) ) {
			return array();
		}

		_prime_post_caches( $cached['ids'], true, true );

		$post_objects = array();
		foreach ( $cached['ids'] as $post_id ) {
			$post = get_post( $post_id );
			if ( $post instanceof WP_Post ) {
				$post_objects[] = $post;
			}
		}

		return $post_objects;
	}

	// Hook to save results on cache miss
	add_filter(
		'posts_results',
		function( $results, $q ) use ( $cache_key, $query ) {
			if ( $q === $query ) {
				$ids = array();
				foreach ( $results as $r ) {
					if ( isset( $r->ID ) ) {
						$ids[] = (int) $r->ID;
					}
				}
				$data = array(
					'ids'           => $ids,
					'found_posts'   => (int) $query->found_posts,
					'max_num_pages' => (int) $query->max_num_pages,
				);
				set_transient( $cache_key, $data, 15 * MINUTE_IN_SECONDS );
			}
			return $results;
		},
		10,
		2
	);

	return $posts;
}
add_filter( 'posts_pre_query', 'eghtesadran_cache_search_pre_query', 10, 2 );
