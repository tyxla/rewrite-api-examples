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
 * You can add a rewrite rule for that purpose.
 * The rewrite rule in question would match the /event/{YEAR}/{EVENT-SLUG} pattern.
 * To achieve this, you would map the `{EVENT-SLUG}` to the `name` query var.
 * A working regex for year is [0-9]{4}, which means: 4 consecutive digits.
 * A working regex for slug is: [^/]+, which means: every character but "/", as many times as possible, but at least once.
 * We're wrapping the slug regex with parentheses because we need to send it to the query arguments.
 * Also, for best results it is always good to restrict by post type. To do this, the `post_type` => `event` is added as well.
 *
 * Note: This is usually only part of the solution. In a real life project you would probably want to:
 * 1. Filter the event links (you can use the `post_type_link` filter for that);
 * 2. Pass the year to the query arguments as well (a corresponding rewrite tag or a custom query var would have to be registered for that).
 */

add_action( 'init', 'rae_add_custom_rewrite_rules' );
function rae_add_custom_rewrite_rules() {
	// This will match the /event/2015/event-slug/ URL and will tell WordPress to load the corresponding event page.
	add_rewrite_rule( '^event/[0-9]{4}/([^/]+)/?$', 'index.php?name=$matches[1]&post_type=event', 'top' );
}