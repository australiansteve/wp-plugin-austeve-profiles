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
			<?php $image = get_field('profile-picture');

			//var_dump($image); ?>

			<a href="<?php echo get_permalink(); ?>">
				<img src='<?php echo $image['sizes']['thumbnail'] ?>'/>
			</a>
		</div>

		<div class="col-xs-12 col-sm-9">

			<a href="<?php echo get_permalink(); ?>">
				<h2 class="profile-name">
				<?php $user = get_field('profile-user'); 
				//var_dump($user);
				echo $user['user_firstname']." ".$user['user_lastname'];
				?>
				</h2>
			</a>

			<p class="profile-membership-type"><?php 
			$field = get_field_object('profile-membership-type');
			$value = get_field('profile-membership-type');
			$label = $field['choices'][ $value ];

			echo $label; ?></p>

			<p class="profile-blurb"><em><?php echo get_field('profile-blurb'); ?></em></p>

		</div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<p class="profile-view-link"><a href="<?php echo get_permalink(); ?>">View profile <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a></p>
		</div>
	</div>

</div>
