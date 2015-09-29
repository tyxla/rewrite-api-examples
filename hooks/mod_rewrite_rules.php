<?php
/**
 * ## Scenario
 * You're using SVG files in your theme or plugin.
 * But some servers don't recognize the SVG files and serve them with `text/plain` MIME type.
 *
 * ## Goal
 * You want to make sure all Apache-based servers properly serve the SVG files.
 *
 * ## Solution
 * You can add the necessary directives using the `mod_rewrite_rules` filter. 
 * By using this filter the directives will be added to the .htaccess file.
 * This way the directives will persist after regenerating the permalink structure.
 */

add_filter( 'mod_rewrite_rules', 'rae_mod_rewrite_rules' );
function rae_mod_rewrite_rules( $rules ) {
	// Declare directives for Apache SVG support
	$new_rules = "
# Adding SVG file support
AddType image/svg+xml svg
AddType image/svg+xml svgz
AddEncoding x-gzip .svgz

";

	// Prepending the new rules to the original rules
	$rules = $new_rules . $rules;

	// Return the updated rules for insertion in .htaccess
	return $rules;
}