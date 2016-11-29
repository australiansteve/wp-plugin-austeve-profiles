<?php
/**
 * Template part for displaying single profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Profiles
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<div class="row profile-picture">
			<?php $image = get_field('profile-picture'); ?>
			<?php if ($image) { ?>
				<img src='<?php echo $image['sizes']['medium'] ?>'/>
			<?php } else  { ?>
			<!-- Display placeholder image -->
				<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/placeholder-person.jpg'; ?>' alt='Profile picture' height='150px' width='150px'/>
			<?php } ?>
		</div>

		<div class="row profile-blurb">
			<?php $blurb = get_field('profile-blurb'); 
			if ($blurb)
			{
				echo "<em>".$blurb."</em>";
			}
			?>
		</div>

		<div class="row profile-social">
			<?php $website = get_field('profile-website'); 
			$firstname = get_field('profile-firstname');
			
			if (substr($firstname, strlen($firstname) - 1, 1) === 's') {
				$firstname.='\'';
			}
			else {
				$firstname.='\'s';
			}

			if ($website)
			{
				echo "<a href='".$website."' target='blank' title=\"Visit ".$firstname." website\" alt='Website'><i class='fa fa-2x fa-globe' aria-hidden='true'></i></a>";
			}
			?>
			<?php $facebook = get_field('profile-facebook'); 
			if ($facebook)
			{
				echo "<a href='".$facebook."' target='blank' title=\"Visit ".$firstname." Facebook profile\" alt='Facebook'><i class='fa fa-2x fa-facebook-official' aria-hidden='true'></i></a>";
			}
			?>
			<?php $twitter = get_field('profile-twitter'); 
			if ($twitter)
			{
				echo "<a href='".$twitter."' target='blank' title=\"Visit ".$firstname." Twitter profile\" alt='Twitter'><i class='fa fa-2x fa-twitter' aria-hidden='true'></i></a>";
			}
			?>
			<?php $instagram = get_field('profile-instagram'); 
			if ($instagram)
			{
				echo "<a href='".$instagram."' target='blank' title=\"Visit ".$firstname." Instagram profile\" alt='Instagram'><i class='fa fa-2x fa-instagram' aria-hidden='true'></i></a>";
			}
			?>
			<?php $tumblr = get_field('profile-tumblr'); 
			if ($tumblr)
			{
				echo "<a href='".$tumblr."' target='blank' title=\"Visit ".$firstname." Tumblr profile\" alt='Tumblr'><i class='fa fa-2x fa-tumblr' aria-hidden='true'></i></a>";
			}
			?>
		</div>

		<?php $location = get_field('profile-location'); 
		if ($location)
		{

			echo '<div class="row profile-location">';
			echo "<label>Location: </label> ".$location;
			echo '</div>';
		}
		?>

		<?php $mediums = get_field('profile-mediums'); 

		if ($mediums) {
		?>
			<div class="row profile-mediums">
				<label>Media:</label>
				<?php 
				$medialist = "";
				
				foreach ($mediums as $medium)
				{
					$medialist .= "<a href='".get_term_link($medium)."'>";
					$medialist .= $medium->name;
					$medialist .= "</a>, ";
				}
				$medialist = substr($medialist, 0, strlen($medialist) - 2);
				
				echo $medialist;
				?>
			</div>
		<?php
		}
		?>
		<div class="row profile-about">
			<?php $about = get_field('profile-about'); 
			if ($about)
			{
				echo $about;
			}
			?>
		</div>

		<div class="row profile-portfolio">
			<?php $portfolio = get_field('profile-portfolio'); 
			
			if ($portfolio)
			{
				echo "<h2>Portfolio</h2>";
				foreach ($portfolio as $project) {

					echo "<div class='portfolio-project'>";
										
					if (isset($project['title'])) {	
						echo "<h3 class='project-title'>".$project['title']."</h3>";
					}
					if (isset($project['description'])) {					
						echo "<span class='project-description'>".$project['description']."</span>";	
					}	
					if (isset($project['artwork'])) {					
						echo "<div class='project-work'>";

						foreach ($project['artwork'] as $piece) {

							echo "<div class='project-piece'>";

							if (isset($piece['image'])) {					
								echo "<img class='work-image' src='".$piece['image']['url']."'/>";	
							}
							if (isset($piece['audiovideo'])) {
								echo "<span class='work-av'>";
								echo $piece['audiovideo'];
								echo "</span>";
							}
							if (isset($piece['formatted-text'])) {
								echo "<span class='work-text'>";
								echo $piece['formatted-text'];
								echo "</span>";
							}
							if (isset($piece['description'])) {
								echo "<p class='work-description'>".$piece['description']."</p>";
							}
							echo "</div> <!-- .project-piece -->";
						}

						echo "</div>";	
					}		
					/**/

					echo "</div> <!-- .portfolio-project-->";
				}
			}
			?>
		</div>

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
