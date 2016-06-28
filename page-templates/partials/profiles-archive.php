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
				<?php the_title( '<h2 class="profile-title">', '</h2>' ); ?>
			</a>

			<p class="profile-blurb"><em><?php echo get_field('profile-blurb'); ?></em></p>
		</div>

	</div>

</div>
