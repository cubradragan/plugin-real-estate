<?php
function real_estate_pt() {
    $args = array(
        'public'      => true,
        'label'       => __( 'Real Estate', 'textdomain' ),
        'has_archive' => true,
        'menu_icon'   => 'dashicons-book',
        'rewrite'     => array(
        'slug'        => '/estates/%type%',
        'with_front'       => true
         )
    );
    register_post_type( 'real_estate', $args );
}
add_action( 'init', 'real_estate_pt' );

add_action( 'init', 'create_real_estate_taxonomies', 0 );

// create two taxonomies, location and type
function create_real_estate_taxonomies() {
	// Add new taxonomy, location
	$labels = array(
		'name'              => _x( 'Location', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Location', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Location', 'textdomain' ),
		'all_items'         => __( 'All Location', 'textdomain' ),
		'edit_item'         => __( 'Edit Location', 'textdomain' ),
		'update_item'       => __( 'Update Location', 'textdomain' ),
		'add_new_item'      => __( 'Add New Location', 'textdomain' ),
		'new_item_name'     => __( 'New Genre Location', 'textdomain' ),
		'menu_name'         => __( 'Location', 'textdomain' ),
	);

  $args = array(
    'hierarchical'          => false,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'location', ),
  );

	register_taxonomy( 'location', array( 'real_estate' ), $args );

	// Add new taxonomy, Type
	$labels = array(
		'name'                       => _x( 'Type', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Type', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Search Type', 'textdomain' ),
		'popular_items'              => __( 'Popular Type', 'textdomain' ),
		'all_items'                  => __( 'All Type', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Type', 'textdomain' ),
		'update_item'                => __( 'Update Type', 'textdomain' ),
		'add_new_item'               => __( 'Add New Type', 'textdomain' ),
		'new_item_name'              => __( 'New Type Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate Type with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove Type', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Type', 'textdomain' ),
		'not_found'                  => __( 'No Type found.', 'textdomain' ),
		'menu_name'                  => __( 'Type', 'textdomain' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'estates' ),
	);

	register_taxonomy( 'type', 'real_estate', $args );
}

function filter_post_type_link_realestate( $link, $post ) {
  if ( $post->post_type == 'real_estate' ) {
    if ( $cats = get_the_terms( $post->ID, 'type' ) ) {
      $link = str_replace( '%type%', current( $cats )->slug, $link );
    }
  }
  return $link;
}
add_filter( 'post_type_link', 'filter_post_type_link_realestate', 10, 2 );
