<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AUSteve Profiles
 */

get_header(); ?>

<div class="row"><!-- .row start -->

	<div class="col-sm-12"><!-- .columns start -->

		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="row">
				
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

							<?php include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php'); ?>
							
					<?php endwhile; ?>

				</div> <!-- .row-->

				<?php the_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'page-templates/partials/content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->

	</div><!-- .columns end -->

</div><!-- .row end -->

<?php get_footer(); ?>
