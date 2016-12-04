<?php
/**
 * Template part for displaying the archive of profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Projects
 */
?>
<div class="row">

	<div class="small-12 columns profile-archive-item">

		<div class="row">

			<div class="columns small-3 profile-image">
				<?php 
				//echo the_ID();
				$image = get_field('picture'); 
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

			<div class="columns small-9">

				<div class="row columns">
					<a href="<?php echo get_permalink(); ?>">
						<h2 class="profile-name">
						<?php $user = get_field('user'); 
						if (is_numeric($user))
						{
							//Sometimes the user is returned as a number (the id)
							$userobject = get_user_by('id', $user);
							echo $userobject->user_firstname." ".$userobject->user_lastname;
						}
						else 
						{
							//var_dump($user);
							echo $user['user_firstname']." ".$user['user_lastname'];
						}
						?>
						</h2>
					</a>

					<p class="profile-about"><em><?php echo get_field('blurb'); ?></em></p>
				</div>

				<div class="row columns ">
					<a class="button" href="<?php echo get_permalink(); ?>">Donate to <?php echo get_field('firstname'); ?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
				</div>

			</div>

		</div>

	</div>

</div>
