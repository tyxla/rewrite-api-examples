<?php
/**
 * ## Scenario
 * You have a regular site where you want to have different frontend views for public post types (like pages & posts).
 * Each different view would display the content in a different template.
 * Possible views can be: standard, print, gallery, slideshow, etc.
 *
 * ## Goal
 * You want to support view URLs like: /test-post/view/standard/ and /test-post/view/print/ for all public post types.
 *
 * ## Solution
 * You can add a rewrite endpoint for that purpose.
 * The endpoint masks should be `EP_PERMALINK | EP_PAGES`, which matches both pages and the rest of the public post types.
 * This will also register a query var that you can use for fetching the current view.
 *
 * Note: This is usually only part of the solution. In a real life project, in addition to this solution you would probably want to do either of the following:
 * A: Use the `template_include` filter to determine which template to use; 
 * B: Implement the template inclusion custom logic in your singular template by checking get_query_var( 'view' ).
 */

// Register custom rewrite endpoints
add_action( 'init', 'rae_add_custom_rewrite_endpoints' );
function rae_add_custom_rewrite_endpoints() {
	add_rewrite_endpoint( 'view', EP_PERMALINK | EP_PAGES );
}