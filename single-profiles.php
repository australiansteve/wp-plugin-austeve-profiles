<?php
/**
 * The template for displaying all single profiles.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AUSteve Projects
 */
?>

<?php get_header(); ?>

<div class="row"><!-- .row start -->

	<div class="columns small-12"><!-- .columns small-12 start -->

		<div id="primary" class="content-area">

			<div id="content" class="site-content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php 
						
	            		if (locate_template('page-templates/partials/profiles-single.php') != '') {
							// yep, load the page template
							get_template_part('page-templates/partials/profiles', 'single');
						} else {
							// nope, load the default
							include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-single.php');
						}
						
					?>			

				<?php endwhile; ?>

			</div><!-- #content -->

		</div><!-- #primary -->

	</div><!-- .columns small-12 end -->

</div><!-- .row end -->

<?php get_footer(); ?>
