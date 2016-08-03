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
include( plugin_dir_path( __FILE__ ) . 'AUSteveProfilesPageTemplater.php');

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
		'supports'            => array( 'author', 'revisions', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'medium' ),
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

	$taxonomyLabels = array(
		'name'              => _x( 'Mediums', 'taxonomy general name' ),
		'singular_name'     => _x( 'Medium', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Mediums' ),
		'all_items'         => __( 'All Mediums' ),
		'parent_item'       => __( 'Parent Medium' ),
		'parent_item_colon' => __( 'Parent Parent:' ),
		'edit_item'         => __( 'Edit Medium' ),
		'update_item'       => __( 'Update Medium' ),
		'add_new_item'      => __( 'Add New Medium' ),
		'new_item_name'     => __( 'New Medium' ),
		'menu_name'         => __( 'Mediums' ),
	);

	$taxonomyArgs = array(

		'label'               => __( 'austeve_mediums', 'austeve-profiles' ),
		'labels'              => $taxonomyLabels,
		'show_admin_column'	=> false,
		'hierarchical' 		=> false,
		'rewrite'           => array( 'slug' => 'medium' ),
		'capabilities'		=> array(
							    'manage_terms' => 'manage_categories',
							    'edit_terms' => 'manage_categories',
							    'delete_terms' => 'manage_categories',
							    'assign_terms' => 'edit_posts'
							 )
		);

	register_taxonomy( 'austeve_mediums', 'austeve-profiles', $taxonomyArgs );

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
	wp_enqueue_style( 'fontawesome_styles', plugin_dir_url( __FILE__ ). '/assets/dist/css/font-awesome.css', '', '9' );
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
	ob_start();
    $args = array(
        'post_type' => 'austeve-profiles',
        'post_status' => array('publish', 'pending'),
        'meta_key'        => 'profile-lastname',
        'orderby'        => 'meta_value',
    	'order'          => 'ASC',
		'posts_per_page' => -1
    );

    echo '<div class="row archive-container">';
    $query = new WP_Query( $args );
    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
                       
            if (get_post_status() == 'publish') {
				include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php');
			}
			else {
				include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive-pending.php');
			}		           
        }
    }
    echo '</div>';
    
    wp_reset_postdata();
    return ob_get_clean();
}

function austeve_profiles_modify_post_title( $post_id )
{
	if ('austeve-profiles' != get_post_type($post_id) || wp_is_post_revision($post_id)) {
		return;
	}

	$user = get_field('profile-user'); 
	update_field( 'profile-firstname', $user['user_firstname'],  $post_id );
	update_field( 'profile-lastname', $user['user_lastname'],  $post_id );
	
	remove_action( 'acf/save_post', 'modify_post_title' , 50);
	wp_update_post( array( 'ID' => $post_id, 'post_title' => $user['user_firstname']." ".$user['user_lastname'] ) );
	add_action( 'acf/save_post', 'modify_post_title' , 50);
}

add_action( 'acf/save_post' , 'austeve_profiles_modify_post_title' , 50 ); //Priority of 50 means this is called after the post has actually been saved

function austeve_profiles_save_as_pending( $post_id ) {

	//Return is it's not saving from the front end, or isn't a profile
	if (is_admin() || get_post_type($post_id) != 'austeve-profiles'){
		return $post_id;
	}

	//Otherwise, set the post status to draft & save!
	$args = array (
		'ID' => $post_id,
		'post_status' => 'pending'
		);
	wp_update_post($args);
	return $post_id;
}

add_action( 'acf/pre_save_post' , 'austeve_profiles_save_as_pending' , 10, 1 ); 


function update_post_title_with_new_username( $user_id, $old_user_data ) 
{
	error_log("User ".$user_id." updated. ".$old_user_data->user_firstname." ".$old_user_data->user_lastname );

	// args
	$args = array(
		'numberposts'	=> -1,
		'post_type'		=> 'austeve-profiles',
		'meta_key'		=> 'profile-user',
		'meta_value'	=> $user_id
	);

	// query
	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ):
			while( $the_query->have_posts() ) : $the_query->the_post();

				if ($old_user_data->user_firstname !== get_field('profile-firstname'))
				{
					update_field('profile-firstname', $old_user_data->user_firstname);
				}

				if ($old_user_data->user_lastname !== get_field('profile-lastname'))
				{
					update_field('profile-lastname', $old_user_data->user_lastname);
				}

			endwhile;
	endif;

}
add_action( 'profile_update', 'update_post_title_with_new_username', 5, 2);

//Adds a page template so that 'Edit Profile' can be done
add_action( 'plugins_loaded', array( 'AUSteveProfilesPageTemplater', 'get_instance' ) );

?>