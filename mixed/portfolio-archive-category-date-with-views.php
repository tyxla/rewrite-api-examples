<?php

/**
 * ## Scenario
 * You're building a portfolio section, represented by the `project` post type.
 * Portfolio entries can be categorized by the `project_category` taxonomy.
 *
 * ## Goal
 * You want to support mixed portfolio archives by date (year, month, day) and category.
 * Categories can be hierarchical, which should be respected in the permalinks.
 * There should be an unfiltered archives page that lists all portfolio projects.
 * All types of archive pages should support pagination.
 * Also, you want the portfolio project permalink to contain the category and date.
 * In addition, each project should have 2 additional views: gallery and slideshow.
 * At the end, the following links should be supported:
 * - /portfolio/
 * - /portfolio/parent-category/
 * - /portfolio/parent-category/child-category/
 * - /portfolio/parent-category/2015/
 * - /portfolio/parent-category/2015/10/
 * - /portfolio/parent-category/2015/10/24/
 * - /portfolio/parent-category/2015/10/24/project-name/
 * - /portfolio/parent-category/2015/10/24/project-name/gallery/
 * - /portfolio/parent-category/2015/10/24/project-name/slideshow/
 * 
 * ## Solution
 * We're registering the `project` post type and `project_category` taxonomy.
 * The default `rewrite` is disabled, because we'll be building custom rewrite structure.
 * 
 * We're registering 2 custom rewrite rules: for main archive (/projects/) and for its pagination (/projects/page/5/).
 * Also, we're registering the `project` and `project_category` rewrite tags, because they are will not be registered automatically (because `rewrite` is `false`).
 * We're registering the `gallery` and `slideshow` endpoints for posts (the `EP_PERMALINK` endpoint mask).
 * 
 * To build the necessary rewrite rules we define a custom permastruct with the specified structure.
 * We specify `EP_PERMALINK` as the `ep_mask` in order to support the custom `gallery` and `slideshow` endpoints.
 * This permastruct generates most of the necessary rewrite rules (as specified in `rae_add_custom_rewrite_permastructs()` below).
 */

// Register custom post types
add_action( 'init', 'rae_register_post_types' );
function rae_register_post_types() {
	register_post_type( 'project', array(
		'labels' => array(
			'name' => __( 'Projects' ),
			'singular_name' => __( 'Project' ),
			'add_new' => __( 'Add New' ),
			'add_new_item' => __( 'Add new Project' ),
			'view_item' => __( 'View Project' ),
			'edit_item' => __( 'Edit Project' ),
			'new_item' => __( 'New Project' ),
			'view_item' => __( 'View Project' ),
			'search_items' => __( 'Search Projects' ),
			'not_found' =>  __( 'No Projects found' ),
			'not_found_in_trash' => __( 'No Projects found in trash' ),
		),
		'public' => true,
		'rewrite' => false,
		'hierarchical' => false,
	) );
}

// Register custom taxonomies
add_action( 'init', 'rae_register_post_taxonomies' );
function rae_register_post_taxonomies() {
	register_taxonomy( 'project_category', 'project', array(
		'labels' => array(
			'name'              => __( 'Project Categories' ),
			'singular_name'     => __( 'Project Category' ),
			'search_items'      => __( 'Search Project Categories' ),
			'all_items'         => __( 'All Project Categories' ),
			'parent_item'       => __( 'Parent Project Category' ),
			'parent_item_colon' => __( 'Parent Project Category:' ),
			'view_item'         => __( 'View Project Category' ),
			'edit_item'         => __( 'Edit Project Category' ),
			'update_item'       => __( 'Update Project Category' ),
			'add_new_item'      => __( 'Add New Project Category' ),
			'new_item_name'     => __( 'New Project Category Name' ),
			'menu_name'         => __( 'Project Categories' ),
		),
		'hierarchical' => true,
		'rewrite' => false,
	) );
}

// Add custom rewrite rules
add_action( 'init', 'rae_add_custom_rewrite_rules' );
function rae_add_custom_rewrite_rules() {
	// Portfolio post type archive - /portfolio/
	add_rewrite_rule( '^portfolio/?$', 'index.php?post_type=project', 'top' );

	// Pagination for portfolio archive - /portfolio/page/5/
	add_rewrite_rule( '^portfolio/page/?([0-9]{1,})/?$', 'index.php?post_type=project&paged=$matches[1]', 'top' );
}

// Add custom rewrite tags
add_action( 'init', 'rae_add_custom_rewrite_tags' );
function rae_add_custom_rewrite_tags() {
	// Rewrite tag for the Project post type - non hierarchical
	add_rewrite_tag( "%project%", '([^/]+)' );

	// Rewrite tag for the Project Category taxonomy - hierarchical
	add_rewrite_tag( "%project_category%", '(.+?)' );
}

// Add custom rewrite endpoints
add_action( 'init', 'rae_add_custom_rewrite_endpoints' );
function rae_add_custom_rewrite_endpoints() {
	// Custom /gallery/ view for single project pages
	add_rewrite_endpoint( 'gallery', EP_PERMALINK );

	// Custom /slideshow/ view for single project pages
	add_rewrite_endpoint( 'slideshow', EP_PERMALINK );
}

// Add custom rewrite permastructs
add_action( 'init', 'rae_add_custom_rewrite_permastructs' );
function rae_add_custom_rewrite_permastructs() {
	/* 
	 * Our main permastruct that will support the following URLs:
	 * - /portfolio/web/ - project category
	 * - /portfolio/web/coding/ - project category (child)
	 * - /portfolio/web/2015/ - year archive for that project category
	 * - /portfolio/web/2015/10/ - month archive for that project category
	 * - /portfolio/web/2015/10/24/ - day archive for that project category
	 * - /portfolio/web/2015/10/24/test-website/ - single portfolio item
	 */
	add_permastruct( 
		'portfolio', 
		'portfolio/%project_category%/%year%/%monthnum%/%day%/%project%/', 
		array(
			'ep_mask' => EP_PERMALINK,
		)
	);
}
