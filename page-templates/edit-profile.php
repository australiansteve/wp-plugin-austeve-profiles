<?php
/**
 * Template Name: Edit Profile Page
 *
 * @package AUSteve Profiles 
 * Author: AustralianSteve
 * Author URI: http://AustralianSteve.com
 */
?>

<?php acf_form_head(); ?>
<?php get_header(); ?>

<div class="row"><!-- .row start -->

	<div class="col-sm-12"><!-- .col-sm-12 start -->

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php 
			//New query to get current user profile
			if ( is_user_logged_in() ) {
			    $current_user = wp_get_current_user();
		        printf( 'Welcome, %s!', esc_html( $current_user->user_firstname ) );

				// args
				$args = array(
					'numberposts'	=> 1,
					'post_type'		=> 'austeve-profiles',
					'post_status'	=> array ('publish', 'pending'),
					'meta_key'		=> 'profile-user',
					'meta_value'	=> ''.$current_user->ID
				);

				// query
				$the_query = new WP_Query( $args );

				if( $the_query->have_posts() ): 
					while( $the_query->have_posts() ) : $the_query->the_post();
						
		            	acf_form(array(
							'post_id'	=> the_ID(),
							'post_title'	=> false,
							'submit_value'	=> 'Update Profile',
							'updated_message' => __('Profile saved.', 'austeve-profiles'),
							'fields' => array ( 'profile-picture',
								'profile-location', 
								'profile-mediums', 
								'profile-blurb', 
								'profile-about', 
								'profile-website', 
								'profile-facebook', 
								'profile-twitter',
								'profile-instagram',
								'profile-tumblr'
								),
						));

					endwhile; 
				else: 
					echo "<p>Profile not found. User ".$current_user->ID ;
				endif; 

				wp_reset_query();	 // Restore global post data stomped by the_post().

			} else {
			    echo "<p>You are not logged in. <a href='".esc_url( wp_login_url() )." alt='Login'>Log in now</a>";
			}

			?>

		</div><!-- #content -->
	</div><!-- #primary -->

	</div><!-- .col-sm-12 end -->

</div><!-- .row end -->

<?php get_footer(); ?>
