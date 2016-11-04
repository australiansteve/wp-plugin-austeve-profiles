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

	<div class="columns small-12"><!-- .col-sm-12 start -->

	<div id="primary" class="content-area">
		<div id="content" class="site-content edit-profile-page" role="main">

			<?php 
			//New query to get current user profile
			if ( is_user_logged_in() ) {
			    $current_user = wp_get_current_user();
		        
			    echo "<h1 class='entry-title'>".$current_user->user_firstname." ".$current_user->user_lastname."</h1>";

			    //Echo help link
		        if ( get_option('austeve_profile_help_page'))
		        {
				    echo "<div style='text-align: right; width: 100%'><a href='".get_permalink(get_option('austeve_profile_help_page'))."' target='blank' title='Profile help'>";
				    echo "Help <i class='fa fa-question-circle' style='margin: 0 5px;' aria-hidden='true'></i>";
				    echo "</a></div>";
				}

				// args
				$args = array(
					'numberposts'	=> 1,
					'post_type'		=> 'austeve-profiles',
					'post_status'	=> array ('publish'),
					'meta_key'		=> 'profile-user',
					'meta_value'	=> ''.$current_user->ID
				);

				// query
				$the_query = new WP_Query( $args );

				if( $the_query->have_posts() ): 
					while( $the_query->have_posts() ) : $the_query->the_post();
						
						$message = "Changes saved. <p><a href='".get_permalink()."' target='blank'>View profile</a></p>";
		            	acf_form(array(
							'post_id'	=> get_the_ID(),
							'post_title'	=> false,
							'submit_value'	=> 'Update Profile',
							'updated_message' => __($message, 'austeve-profiles'),
							'fields' => array ( 'picture',
									'about'
								),
						));

					endwhile; 
				else: 
					
					?>

				<p>Create your new profile below</p>
				<?php
					$slug = str_replace(' ', '-', $current_user->user_firstname." ".$current_user->user_lastname);

					$message = "Profile saved successfully. <p><a href='".home_url()."' target='blank'>Return home to view your profile</a></p>";

	            	acf_form(array(
						'post_id'	=> 'new_post',
						'post_title'	=> false,
						'new_post' => array(
							'post_type'		=> 'austeve-profiles',
							'post_status'	=> 'publish',
							'post_title' => $current_user->user_firstname." ".$current_user->user_lastname, 
							'post_name' => $slug
							),
						'submit_value'	=> 'Create Portfolio',
						'updated_message' => __($message, 'austeve-profiles'),
						'fields' => array ( 'picture',
								'about'
							),
					));

				endif; 

				wp_reset_query();	 // Restore global post data stomped by the_post().

			} else {
			    echo "<h1>Dipsters only</h1>";
			    echo "<p>Only registered dipsters can view this page. <a href='".esc_url( wp_login_url() )."' alt='Login'>Log in now</a>";
			}

			acf_enqueue_uploader();
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

	</div><!-- .columns small-12 end -->

</div><!-- .row end -->

<?php get_footer(); ?>
