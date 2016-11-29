<?php
/**
 * Template part for displaying the archive of profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Projects
 */
?>
<div class="col-xs-12 col-sm-6 col-md-12 profile-archive-item">

	<div class="row">

		<div class="col-xs-12 col-sm-3 profile-image">
			<?php 
			//echo the_ID();
			$image = get_field('profile-picture'); 
			//var_dump($image);
			?>

			<?php if ($image) { ?>
			<a href="<?php echo get_permalink(); ?>">
				<img src='<?php echo $image['sizes']['thumbnail'] ?>'/>
			</a>
			<?php } else  { ?>
			<!-- Display placeholder image -->
			<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/placeholder-person.jpg'; ?>' alt='Profile picture' height='150px' width='150px'/>
			<?php } ?>

		</div>

		<div class="col-xs-12 col-sm-9">

			<a href="<?php echo get_permalink(); ?>">
				<h2 class="profile-name">
				<?php $user = get_field('profile-user'); 
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
				?>
				</h2>
			</a>

			<p class="profile-membership-type"><?php 
			$field = get_field_object('profile-membership_type');
			$value = get_field('profile-membership_type');
			$label = $field['choices'][ $value ];

			echo $label;
			
			$location = get_field('profile-location');

			if ($location)
			{
				echo " - ".$location;
			}
			?>
			</p>

			<p class="profile-blurb"><em><?php echo get_field('profile-blurb'); ?></em></p>

		</div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<p class="profile-view-link"><a href="<?php echo get_permalink(); ?>">View portfolio <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a></p>
		</div>
	</div>

</div>
