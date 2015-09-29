<?php
/**
 * ## Scenario
 * You have WordPress setup in IIS 7 or higher.
 * Also you have an old website /oldsite that you want to permanently redirect to http://example.com/.
 *
 * ## Goal
 * You want to add the redirects in the web.config file so they are performed at a server level before reaching WordPress.
 * Also you don't want your modifications to be lost from the web.config file when doing updates to it.
 *
 * ## Solution
 * You can add the necessary redirects using the `iis7_url_rewrite_rules` filter. 
 * This way they will persist after regenerating the permalink structure.
 */

add_filter( 'iis7_url_rewrite_rules', 'rae_iis7_url_rewrite_rules' );
function rae_iis7_url_rewrite_rules( $rules ) {
	// Redirection rules
	$new_rules = '
	<location path="oldsite">
		<system.webServer>
		<httpRedirect enabled="true" destination="http://example.com" httpResponseStatus="Permanent" />
		</system.webServer>
	</location>
';

	// Inserting the new rules at the root of <configuration>
	$marker = '<configuration>';
	$rules = str_replace( $marker, $marker . $new_rules, $rules );

	// Return the updated rules for insertion in web.config
	return $rules;
}