<?php
/**
 * Plugin Name: Profiles - ArtsLink NB
 * Plugin URI: https://github.com/australiansteve/wp-plugin-austeve-profiles
 * Description: Add, edit & display user profiles
 * Version: 0.0.1
 * Author: AustralianSteve
 * Author URI: http://AustralianSteve.com
 * License: GPL2
 */

include( plugin_dir_path( __FILE__ ) . 'admin.php');
include( plugin_dir_path( __FILE__ ) . 'widget.php');

/*
* Creating a function to create our CPT
*/

function austeve_create_profiles_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Profiles', 'Post Type General Name', 'austeve-profiles' ),
		'singular_name'       => _x( 'Profile', 'Post Type Singular Name', 'austeve-profiles' ),
		'menu_name'           => __( 'Profiles', 'austeve-profiles' ),
		'parent_item_colon'   => __( 'Parent Profile', 'austeve-profiles' ),
		'all_items'           => __( 'All Profiles', 'austeve-profiles' ),
		'view_item'           => __( 'View Profile', 'austeve-profiles' ),
		'add_new_item'        => __( 'Add New Profile', 'austeve-profiles' ),
		'add_new'             => __( 'Add New', 'austeve-profiles' ),
		'edit_item'           => __( 'Edit Profile', 'austeve-profiles' ),
		'update_item'         => __( 'Update Profile', 'austeve-profiles' ),
		'search_items'        => __( 'Search Profile', 'austeve-profiles' ),
		'not_found'           => __( 'Not Found', 'austeve-profiles' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'austeve-profiles' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Profiles', 'austeve-profiles' ),
		'description'         => __( 'User profiles', 'austeve-profiles' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'author', 'revisions', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'rewrite'           => array( 'slug' => 'profiles' ),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'austeve-profiles', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'austeve_create_profiles_post_type', 0 );

function profile_include_template_function( $template_path ) {
    if ( get_post_type() == 'austeve-profiles' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-profiles.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-profiles.php';
            }
        }
        else if ( is_archive() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'archive-profiles.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/archive-profiles.php';
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'profile_include_template_function', 1 );

function austeve_profiles_enqueue_style() {
	wp_enqueue_style( 'austeve-profiles', plugin_dir_url( __FILE__ ). '/style.css' , false , '4.6'); 
}

function austeve_profiles_enqueue_script() {
	//wp_enqueue_script( 'my-js', 'filename.js', false );
}

add_action( 'wp_enqueue_scripts', 'austeve_profiles_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'austeve_profiles_enqueue_script' );

if ( ! function_exists( 'profile_filter_archive_title' ) ) :
function profile_filter_archive_title( $title ) {

    $title = post_type_archive_title( '', false );

    return $title;

}
endif;

add_filter( 'get_the_archive_title', 'profile_filter_archive_title');

if ( ! function_exists( 'austeve_profiles_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function austeve_profiles_entry_footer() {
	
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'austeve-profiles' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

add_shortcode( 'member_directory', 'austeve_profiles_shortcode_archive' );

function austeve_profiles_shortcode_archive(){
    $args = array(
        'post_type' => 'austeve-profiles'
    );

    $string = '<div class="row">';
    $query = new WP_Query( $args );
    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
            include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php');
        }
    }
    $string .= '</div>';
    wp_reset_postdata();
    return $string;
}
?>