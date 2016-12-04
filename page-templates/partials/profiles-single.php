<?php
/**
 * Template part for displaying single profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Profiles
 */

require_once('wp-config.php');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<div class="row">
			<div class="small-12 medium-6 columns">
				<div class="row columns profile-picture">
					<?php $image = get_field('picture'); ?>
					<?php if ($image) { ?>
						<img src='<?php echo $image['sizes']['medium'] ?>'/>
					<?php } else  { ?>
					<!-- Display placeholder image -->
						<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/placeholder-person.jpg'; ?>' alt='Profile picture' height='150px' width='150px'/>
					<?php } ?>
				</div>

				<div class="row columns profile-blurb">
					<?php $blurb = get_field('blurb'); 
					if ($blurb)
					{
						echo "<em>".$blurb."</em>";
					}
					?>
				</div>

				<div class="row columns profile-about">
					<?php $about = get_field('about'); 
					if ($about)
					{
						echo $about;
					}
					?>
				</div>

				<?php 
				$userGoal = get_field('goal'); 
				$amountRaised = 0;
				$displayDonations = false;
				$donationsHTML = "";

				$donationsHTML .= "<div class='row donation header'>";
				$donationsHTML .= "<div class='small-9 medium-4 columns'>Donor";
				$donationsHTML .= "</div>"; //end .columns
				$donationsHTML .= "<div class='small-3 medium-2 columns'>Amount";
				$donationsHTML .= "</div>"; //end .columns
				$donationsHTML .= "<div class='show-for-medium medium-6 columns'>Message";
				$donationsHTML .= "</div>"; //end .columns
				$donationsHTML .= "</div>"; //end .row


				if ($userGoal > 0) 
				{
					//Formalate URL for getting all donations
					$giveDonationsURL = site_url( '/give-api/donations/?key='.GIVE_KEY.'&token='.GIVE_TOKEN.'&number=-1', 'http' );
					
					$donationArgs = array(
						'timeout'     => 30
						);

					$response = wp_remote_get( $giveDonationsURL, $donationArgs );

					//If it doesn't time out
					if( is_array($response) ) {

						//var_dump($response);
						$header = $response['headers']; // array of http header lines
						$body = $response['body']; // use the content

						$results = json_decode( $body , true );
						//var_dump($results);

						if (is_array( $results ) && array_key_exists('donations', $results))  
						{
							foreach( $results['donations'] as $donation)
							{
								//var_dump($donation);
								if (array_key_exists('user_donation', $donation['payment_meta']) && $donation['payment_meta']['user_donation'] == get_field('user')['ID'])
								{
									$amountRaised += intval($donation['total']);
									$dMessage = array_key_exists('donation_message', $donation['payment_meta']) ? $donation['payment_meta']['donation_message'] : "";

									$displayDonations = true;
									$donationsHTML .= "<div class='row donation'>";
									$donationsHTML .= "<div class='small-9 medium-4 columns'>".$donation['name'];
									$donationsHTML .= "</div>"; //end .columns
									$donationsHTML .= "<div class='small-3 medium-2 columns'>".number_format($donation['total'],2,".",",");
									$donationsHTML .= "</div>"; //end .columns
									$donationsHTML .= "<div class='small-12 medium-6 columns'><em>".$dMessage."</em>"; //Actually show message
									$donationsHTML .= "</div>"; //end .columns
									$donationsHTML .= "</div>"; //end .row
								}
							}
						}
					}
				}
				?>
				<?php 
				$current_user = wp_get_current_user();
				if (get_field('user')['ID'] == $current_user->ID) 
				{
				    // Logged in user viewing own profile = DISPLAY EDIT PROFILE LINK
				?>
					<div class="row columns edit-profile">
						<a class="button" href="<?php echo site_url();?>/edit-profile">Edit my profile</a>
					</div>
				<?php 
				}
				?>
				
			</div>

			<div class="small-12 medium-6 columns">

				<?php 
				if ($userGoal > 0) 
				{
					$percentage = round(($amountRaised / $userGoal) * 100); 
				?>

				<div class="give-goal-progress">
		            <div class="raised">
		            	<span class="income">$<?php echo number_format($amountRaised,2,".",","); ?></span> of <span class="goal-text">$<?php echo number_format($userGoal,2,".",","); ?></span> raised        
		            </div>
		    
		            <div class="give-progress-bar">
			            <span style="width: 0%;background-color:#2bc253"></span>
			        </div><!-- /.give-progress-bar -->
				</div>
				
				<?php 
				}
				else 
				{
					echo "<h2 style='text-align: left;'>Donate to ".get_field('user')['user_firstname']."</h2>";
				}
				?>

				<?php echo do_shortcode('[give_form id="'.GIVE_FORM.'" show_title="false" show_goal="false" display_style="reveal" float_labels="enabled"]'); ?>

				<script>

					//Half a second after page loads populate the hidden field with the user id
					setTimeout(function() {

						jQuery(".give-progress-bar span").each(function() {
							jQuery(this).css('-webkit-transition', 'width 2s');
							jQuery(this).css('width', '<?php echo $percentage; ?>%');
						}); 

						jQuery("#user_donation").each(function() {
								jQuery(this).attr("value", "<?php echo get_field('user')['ID']; ?>");
							}); 

						jQuery("#give-dipster-message").show(); 
					}, 500);
					
				</script>

			</div>

		</div>

		<?php 
		if ($displayDonations)
		{
		?>
		<div class="row columns text-left" style="max-width: 800px; margin:auto">

			<div class="row columns"><h3>Donations to <?php echo get_field('firstname'); ?></h3></div>

			<!-- Display donations to user -->
			<?php echo $donationsHTML; ?>

		</div>
		<?php 
		}
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'austeve-profiles' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php austeve_profiles_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
