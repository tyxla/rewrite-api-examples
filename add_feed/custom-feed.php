<?php
/**
 * ## Scenario
 * You have a standard WordPress blog, nothing specific.
 * 
 * ## Goal
 * You want to add a custom RSS feed
 *
 * ## Solution
 * We're using the add_feed() to add a custom feed.
 * The content of the feed is managed through the callback function - rae_customfeed_display().
 */

// Register custom feeds
add_action( 'init', 'rae_add_custom_rewrite_feeds' );
function rae_add_custom_rewrite_feeds() {
	add_feed( 'customfeed', 'rae_customfeed_display' );
}

function rae_customfeed_display() {
	header('Content-Type: ' . feed_content_type('rss') . '; charset=' . get_option('blog_charset'), true);
	$more = 1;

	echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

	<rss version="0.92">
	<channel>
		<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss('description') ?></description>
		<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
		<docs>http://backend.userland.com/rss092</docs>
		<language><?php bloginfo_rss( 'language' ); ?></language>

		<?php do_action( 'rss_head' ); ?>

		<?php while (have_posts()) : the_post(); ?>
			<item>
				<title><?php the_title_rss() ?></title>
				<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
				<link><?php the_permalink_rss() ?></link>
				<?php do_action( 'rss_item' ); ?>
			</item>
		<?php endwhile; ?>
	</channel>
	</rss>
	<?php
}