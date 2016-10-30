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

	<div class="columns small-12"><!-- .columns start -->

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						//get_template_part( 'components/content', get_post_format() );
						include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php');
					?>

				<?php endwhile; ?>

				<?php the_posts_navigation(array(
		            'prev_text'          => __( 'Next page' ),
		            'next_text'          => __( 'Previous page' ),
		            'screen_reader_text' => __( 'Profiles navigation' ),
		        )); ?>

			<?php else : ?>

				<?php get_template_part( 'components/content', 'none' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
			
		</div><!-- #primary -->

	</div><!-- .columns end -->

</div><!-- .row end -->

<?php get_footer(); ?>
