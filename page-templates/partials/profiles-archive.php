<?php
/**
 * Template part for displaying the archive of profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Projects
 */
?>
<div class="column profile-archive-item">

	<div class="row">

		<div class="columns small-12 medium-3 profile-image">
			<?php 
			//echo the_ID();
			$image = get_field('logo'); 
			//var_dump($image);
			?>

			
			<a href="<?php echo get_permalink(); ?>">
			<?php if ($image) { ?>
				<img src='<?php echo $image['sizes']['thumbnail'] ?>'/>
			<?php } else  { ?>
			<!-- Display placeholder image -->
			<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/placeholder-person.jpg'; ?>' alt='Profile picture' height='150px' width='150px'/>
			<?php } ?>
			</a>

		</div>

		<div class="columns small-12 medium-9">

			<a href="<?php echo get_permalink(); ?>">
				<h2 class="profile-name">
				<?php 
				$organization = get_field('organization_name');

				if ($organization)
				{
					echo $organization;
				}
				else 
				{
					$user = get_field('user'); 
					error_log("User: ".print_r($user, true));
					if (is_numeric($user))
					{
						//Sometimes the user is returned as a number (the id)
						$userobject = get_user_by('id', $user);
						echo $userobject->user_firstname." ".$userobject->user_lastname;
					}
					else 
					{
						echo $user['user_firstname']." ".$user['user_lastname'];
					}
				}
				?>
				</h2>
			</a>

			<?php if (get_field('website')) : ?>
				<p class="contact website"><a href="<?php echo get_field('website'); ?>" target="_blank" alt="View Website">View Website</a></p>
			<?php endif; ?>

		</div>

	</div>

</div>
