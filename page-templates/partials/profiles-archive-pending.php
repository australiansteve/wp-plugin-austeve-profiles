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
			<!-- Display placeholder image -->
			<?php $image = get_field('profile-picture');

			//var_dump($image); ?>

			<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/profile-placeholder.png'; ?>' alt='Profile picture' height='150px' width='150px'/>
		</div>

		<div class="col-xs-12 col-sm-9">

			<h2 class="profile-name">
			<?php $user = get_field('profile-user'); 
			//var_dump($user);
			echo $user['user_firstname']." ".$user['user_lastname'];
			?>
			</h2>

			<p class="profile-membership-type"><?php 
			$field = get_field_object('profile-membership-type');
			$value = get_field('profile-membership-type');
			$label = $field['choices'][ $value ];

			echo $label; ?></p>

		</div>

	</div>

	<div class="row">
		<div class="col-xs-12">
			<p class="profile-view-link">Profile pending review</p>
		</div>
	</div>

</div>
