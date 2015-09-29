<?php
/**
 * ## Scenario
 * You have a public Events post type.
 * URLs of the events are currently like: /event/eventname/.
 *
 * ## Goal
 * You want to support event URLs like: /event/2015/eventname/.
 *
 * ## Solution
 * You can achieve this by registering a `event_year` rewrite tag for the year, and a rewrite rule.
 * The rewrite rule in question would match the /event/{YEAR}/{EVENT-SLUG} pattern.
 * You would want to map the `{EVENT-SLUG}` to the `name` query var, and the year to the custom rewrite tag query var.
 * A working regex for year is [0-9]{4}, which means: 4 consecutive digits.
 * A working regex for slug is: [^/]+, which means: every character but "/", as many times as possible, but at least once.
 * We're wrapping the slug and year regexes with parentheses because we need to send them to the query arguments.
 * Also, for best results it is always good to restrict by post type. To do this, the `post_type` => `event` is added as well.
 *
 * Note: This is usually only part of the solution. In a real life project you would probably want to:
 * 1. Filter the event links (you can use the `post_type_link` filter for that);
 */

// Register custom rewrite tags
add_action( 'init', 'rae_add_custom_rewrite_tags' );
function rae_add_custom_rewrite_tags() {
	add_rewrite_tag('%event_year%', '([0-9]{4})');
}

// Register custom rewrite rules
add_action( 'init', 'rae_add_custom_rewrite_rules' );
function rae_add_custom_rewrite_rules() {
	// This will match the /event/2015/event-slug/ URL and will tell WordPress to load the corresponding event page.
	// Also, the year will be passed to the query, and will be easily accessible by calling get_query_var('event_year').
	add_rewrite_rule( '^event/([0-9]{4})/([^/]+)/?$', 'index.php?event_year=$matches[1]&name=$matches[2]&post_type=event', 'top' );
}
