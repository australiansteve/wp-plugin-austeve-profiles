<?php
/**
 * Template part for displaying the archive of profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Projects
 */
?>
<div class="col-xs-12 col-sm-6 profile-archive-item">

	<div class="row">

		<div class="col-xs-12 col-sm-3">
			<?php $image = get_field('profile-picture'); ?>
			<img src='<?php echo $image['sizes']['medium'] ?>' />
		</div>

		<div class="col-xs-12 col-sm-9">
			<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		</div>

	</div>

</div>
