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

		<div class="row columns profile-picture">
			<?php $image = get_field('picture'); ?>
			<?php if ($image) { ?>
				<img src='<?php echo $image['sizes']['medium'] ?>'/>
			<?php } else  { ?>
			<!-- Display placeholder image -->
				<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/profile-placeholder.png'; ?>' alt='Profile picture' height='150px' width='150px'/>
			<?php } ?>
		</div>

		<div class="row columns profile-about">
			<?php $about = get_field('about'); 
			if ($about)
			{
				echo "<em>".$about."</em>";
			}
			?>
		</div>

		<?php 
		$userGoal = get_field('goal'); 
		$amountRaised = 0;

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

				$header = $response['headers']; // array of http header lines
				$body = $response['body']; // use the content

				$results = json_decode( $body , true );
				//var_dump($results);

				foreach( $results['donations'] as $donation)
				{
					//var_dump($donation);
					if (array_key_exists('user_donation', $donation['payment_meta']) && $donation['payment_meta']['user_donation'] == get_field('user')['ID'])
					{
						$amountRaised += intval($donation['total']);
					}
				}
			}
			
		?>
		<div class="give-goal-progress">
            <div class="raised">
            	<span class="income">$<?php echo number_format($amountRaised,2,".",","); ?></span> of <span class="goal-text">$<?php echo number_format($userGoal,2,".",","); ?></span> raised        
            </div>
    
            <div class="give-progress-bar">
	            <span style="width: 18%;background-color:#2bc253"></span>
	        </div><!-- /.give-progress-bar -->
		</div>
		<?php
		}
		?>

		<?php echo do_shortcode('[give_form id="'.GIVE_FORM.'" show_title="false" show_goal="false" display_style="reveal" float_labels="enabled"]'); ?>

		<script>

			//Half a second after page loads populate the hidden field with the user id
			setTimeout(function() {
				jQuery("#user_donation").each(function() {
						jQuery(this).attr("value", "<?php echo get_field('user')['ID']; ?>");
					}); 
			}, 500);
			
		</script>

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
