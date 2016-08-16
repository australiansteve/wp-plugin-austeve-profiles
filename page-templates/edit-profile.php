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
		<div id="content" class="site-content edit-profile-page" role="main">

			<?php 
			//New query to get current user profile
			if ( is_user_logged_in() ) {
			    $current_user = wp_get_current_user();
		        
			    echo "<h1 class='entry-title'>".$current_user->user_firstname." ".$current_user->user_lastname."</h1>";

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
							'fields' => array ( 'profile-picture',
								'profile-location', 
								'profile-mediums', 
								'profile-blurb', 
								'profile-about', 
								'profile-website', 
								'profile-facebook', 
								'profile-twitter',
								'profile-instagram',
								'profile-tumblr',
								'profile-portfolio'
								),
						));

	            		//Output a preview of the profile image too. This will get moved into position by jQuery when the page loads
		            	$image = get_field('profile-picture');

		            	if ($image)
		            	{
		            		$profilePicturePreview = $image['sizes']['thumbnail']
		         		
        	?>
			        		<div id="profile-picture-preview" class="acf-field acf-field-text">
				        		<div class="acf-label">
									<label>Profile Picture (Preview)</label>
									<p class="description">How your profile picture will be displayed on the Members page (ie. in square form)<br/>
									Profile changes must be saved before this preview gets updated.</p>
								</div>
			        			<img src='<?php echo $profilePicturePreview; ?>'/>
			        		</div>
        	<?php
		            	}
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
						'submit_value'	=> 'Create Profile',
						'updated_message' => __($message, 'austeve-profiles'),
						'fields' => array ( 'profile-picture',
							'profile-location', 
							'profile-mediums', 
							'profile-blurb', 
							'profile-about', 
							'profile-website', 
							'profile-facebook', 
							'profile-twitter',
							'profile-instagram',
							'profile-tumblr',
							'profile-portfolio'
							),
					));

				endif; 

				wp_reset_query();	 // Restore global post data stomped by the_post().

			} else {
			    echo "<h1>Login required</h1>";
			    echo "<p>You are not logged in. <a href='".esc_url( wp_login_url() )."' alt='Login'>Log in now</a>";
			}

			acf_enqueue_uploader();
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

	</div><!-- .col-sm-12 end -->

</div><!-- .row end -->

<?php get_footer(); ?>
