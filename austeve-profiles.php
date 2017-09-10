<?php
/**
 * Plugin Name: Profiles - Sustainable Saint John
 * Plugin URI: https://github.com/australiansteve/wp-plugin-austeve-profiles
 * Description: Add, edit & display user profiles
 * Version: 1.0.0
 * Author: AustralianSteve
 * Author URI: http://AustralianSteve.com
 * License: GPL2
 */

include( plugin_dir_path( __FILE__ ) . 'admin.php');
include( plugin_dir_path( __FILE__ ) . 'shortcode.php');
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
		'parent_item_colon'   => __( 'Parent Profile:', 'austeve-profiles' ),
		'all_items'           => __( 'All Profiles', 'austeve-profiles' ),
		'view_item'           => __( 'View Profile', 'austeve-profiles' ),
		'add_new_item'        => __( 'Add New Profile', 'austeve-profiles' ),
		'add_new'             => __( 'Add New', 'austeve-profiles' ),
		'edit_item'           => __( 'Edit Profile', 'austeve-profiles' ),
		'update_item'         => __( 'Update Profile', 'austeve-profiles' ),
		'search_items'        => __( 'Search Profiles', 'austeve-profiles' ),
		'not_found'           => __( 'Not Found', 'austeve-profiles' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'austeve-profiles' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Profiles', 'austeve-profiles' ),
		'description'         => __( 'Profiles', 'austeve-profiles' ),
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
}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'austeve_create_profiles_post_type', 0 );

function austeve_profiles_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    austeve_create_profiles_post_type();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'austeve_profiles_rewrite_flush' );

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

	if ( WP_DEBUG )
	{
		wp_enqueue_script( 'austeve-profiles-js', plugin_dir_url( __FILE__ ). '/assets/dist/js/front-end.js' , array( 'jquery' ) , '1.0'); 
	}
	else 
	{
		wp_enqueue_script( 'austeve-profiles-js', plugin_dir_url( __FILE__ ). '/assets/dist/js/front-end.min.js' , array( 'jquery' ) , '1.0'); 
	}
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

// array of filters (field key => field name)
$GLOBALS['my_query_filters'] = array( 
	'field_1'	=> 'firstname', 
	'field_2'	=> 'lastname'
);

function austeve_profiles_pre_get_posts_archive($query) {

	//Bail early if is admin or not the profiles archive
	if (is_admin() || !is_post_type_archive('austeve-profiles'))
	{
		return;
	}

	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'austeve-profiles' ) {
	    // Display profiles in lastname order, max of 20 per page
        $query->set( 'posts_per_page', 10 );
        $query->set( 'meta_key', 'lastname' );
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'order', 'ASC' );


		// get meta query
		$meta_query = $query->get('meta_query');

		
		// loop over filters
		foreach( $GLOBALS['my_query_filters'] as $key => $name ) {
			
			// continue if not found in url
			if( empty($_GET[ $name ]) ) {
				
				continue;
				
			}
			
			
			// get the value for this filter
			// eg: http://www.website.com/events?city=melbourne,sydney
			$value = explode(',', $_GET[ $name ]);
			
			
			// append meta query
	    	$meta_query[] = array(
	            'meta_key'		=> $name,
	            'meta_value'		=> $value,
	            'meta_compare'	=> 'LIKE',
	        );
	        
		} 
		
		
		// update meta query
		$query->set('meta_query', $meta_query);

        return;
    }

}
add_action( 'pre_get_posts', 'austeve_profiles_pre_get_posts_archive', 1 );

function austeve_profiles_modify_post_title( $post_id )
{
	error_log("austeve_profiles_modify_post_title");
	if ('austeve-profiles' != get_post_type($post_id) || wp_is_post_revision($post_id)) {
		return;
	}

	$user = get_field('user', $post_id);
	$createUser = get_field('create_user', $post_id);
	$user_email = get_field('email', $post_id);
	$userfirstname = get_field('firstname', $post_id);
	$userlastname = get_field('lastname', $post_id);
	$organization = get_field('organization_name', $post_id);

	error_log("Profile user: ".print_r($user, true));
	//If user field is not set in the profile, create a new user
	if ($createUser && !$user)
	{
		error_log("User field is not set in profile - creating a new one");
		$user_id = username_exists( $user_email );
 
		if ( ! $user_id && false == email_exists( $user_email ) ) {
		    $random_password = wp_generate_password( $length = 20, $include_standard_special_chars = true );
		    $user_id = wp_create_user( $user_email, $random_password, $user_email );
		}

		error_log("New user ID: ".$user_id);
		update_field( 'user', $user_id,  $post_id );
		wp_update_user( array( 'ID' => $user_id, 'first_name' => $userfirstname, 'last_name' => $userlastname ) );
	} 

	$postTitle = ($userfirstname || $userlastname) ? $userfirstname." ".$userlastname : "";
	error_log("Iterim post title: ".$postTitle);
	error_log("Org: ".$organization);
	$postTitle = $organization && strlen(trim($postTitle)) > 0 ? $postTitle." (".$organization.")" : $postTitle;
	$postTitle = strlen(trim($postTitle)) == 0 && $organization ? $organization : $postTitle;
	error_log("Update post title to: ".$postTitle);

	remove_action( 'acf/save_post', 'modify_post_title' , 50);
	$slug = str_replace(' ', '-', $postTitle);
	wp_update_post( array( 'ID' => $post_id, 'post_title' => $postTitle, 'post_name' => $slug ) );
	add_action( 'acf/save_post', 'modify_post_title' , 50);
}

add_action( 'acf/save_post' , 'austeve_profiles_modify_post_title' , 50 ); //Priority of 50 means this is called after the post has actually been saved

function update_post_title_with_new_username( $user_id, $new_user_data ) 
{
	error_log("User ".$user_id." updated. ".$new_user_data->user_firstname." ".$new_user_data->user_lastname );

	// args
	$args = array(
		'numberposts'	=> -1,
		'post_type'		=> 'austeve-profiles',
		'meta_key'		=> 'user',
		'meta_value'	=> $user_id
	);

	// query
	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ):
			while( $the_query->have_posts() ) : $the_query->the_post();

				if ($new_user_data->user_firstname !== get_field('firstname'))
				{
					update_field('firstname', $new_user_data->user_firstname);
				}

				if ($new_user_data->user_lastname !== get_field('lastname'))
				{
					update_field('lastname', $new_user_data->user_lastname);
				}

				austeve_profiles_modify_post_title(get_the_ID());

			endwhile;
	endif;

}
add_action( 'profile_update', 'update_post_title_with_new_username', 5, 2);

//Sanitizes post data to strip out HTML from the profile fields
function austeve_profiles_kses_post( $value ) {
	
	// is array
	if( is_array($value) ) {	
		return array_map('austeve_profiles_kses_post', $value);
	}
	
	// return
	return wp_kses_post( $value );
}
add_filter('acf/update_value', 'austeve_profiles_kses_post', 10, 1);

//Adds a page template so that 'Edit Profile' can be done
add_action( 'plugins_loaded', array( 'AUSteveProfilesPageTemplater', 'get_instance' ) );

?>