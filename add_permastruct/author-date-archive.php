<?php
/**
 * ## Scenario
 * You have a standard WordPress blog, nothing specific.
 * 
 * ## Goal
 * You want to have post archives by both author and date (year, month, day).
 * This would allow URLs like: /archive/username/2015/10/24/
 *
 * ## Solution
 * This can be easily achieved by adding a custom permastruct.
 * In the permastruct we're using the default rewrite tags for author, year, month and day, but custom rewrite tags can be used if necessary.
 * This will generate all of the necessary rewrite rules for the purpose.
 * Since we're using the default `$args`: the `$front` will be prepended to the URLs (if any); also feed and pagination rules will be generated as well.
 */

// Register custom rewrite permastructs
add_action( 'init', 'rae_add_custom_rewrite_permastructs' );
function rae_add_custom_rewrite_permastructs() {
	add_permastruct( 'author_date_archive', 'archive/%author%/%year%/%monthnum%/%day%' );
}