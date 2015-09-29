<?php
/**
 * ## Scenario
 * There is a post type "Events" in your CMS.
 * You have a custom option in your CMS that manages the rewrite slug of that Events post type.
 *
 * ## Goal
 * You want your "Events" permalinks to work after changing the rewrite slug option, but without having to regenerate the permalinks manually.
 *
 * ## Solution
 * You can hook to the `add_option_*`, `update_option_*` and `delete_option_*` hooks, and flush the rewrite rules.
 * This will assure that the rewrite rules are up to date each time this option is changed.
 *
 * Note A: You can easily use the `flush_rewrite_rules` as the callback in the `add_action` call, but here it is assumed that you might want to do something in addition to flushing the rewrite rules.
 * Note B: Make sure that your "Events" post type is registered before the flush occurs.
 */

// Hook on all `rae_events_cpt_rewrite_slug` option actions.
add_action( 'add_option_rae_events_cpt_rewrite_slug', 'rae_update_option_rae_events_cpt_rewrite_slug', 10, 3 );
add_action( 'update_option_rae_events_cpt_rewrite_slug', 'rae_update_option_rae_events_cpt_rewrite_slug', 10, 3 );
add_action( 'delete_option_rae_events_cpt_rewrite_slug', 'rae_update_option_rae_events_cpt_rewrite_slug', 10, 3 );
function rae_update_option_rae_events_cpt_rewrite_slug( $old_value, $value, $option ) {
	// Flush the rewrite rules.
	flush_rewrite_rules();
}