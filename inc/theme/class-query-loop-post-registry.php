<?php
/**
 * Query Loop Post Registry.
 *
 * @package KJRoelke
 */

namespace KJRoelke\Theme;

use WP_Block;
use WP_Query;

/**
 * Prevent posts from appearing in multiple Query Loop blocks.
 */
class Query_Loop_Post_Registry {

	/**
	 * IDs already rendered by a Query Loop.
	 *
	 * @var int[]
	 */
	private static array $used_post_ids = array();

	/**
	 * Excludes posts rendered by previous Query Loop blocks.
	 *
	 * @param array    $query Query arguments.
	 * @param WP_Block $block Block instance.
	 * @return array
	 */
	public function filter_query_vars( $query, $block ) {
		if ( 'core/post-template' !== $block->name ) {
			return $query;
		}

		$query['post__not_in'] = array_values(
			array_unique(
				array_merge(
					$query['post__not_in'] ?? array(),
					self::$used_post_ids
				)
			)
		);

		$query['kjr_track_posts'] = true;

		return $query;
	}

	/**
	 * Registers posts that have been rendered by the current Query Loop.
	 *
	 * @param WP_Query $query The current query instance.
	 * @return void
	 */
	public function register_posts( WP_Query $query ) {
		if ( ! $query->get( 'kjr_track_posts' ) ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			self::$used_post_ids[] = (int) $post->ID;
		}

		self::$used_post_ids = array_values(
			array_unique( self::$used_post_ids )
		);
	}
}
