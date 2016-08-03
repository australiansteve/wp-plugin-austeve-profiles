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
			<div id="content" class="site-content" role="main">

			<?php 
			//Modify query to include Published and Pending profiles. Only display the latest Published revision though
			// args
			$args = array(
		        'post_type' => 'austeve-profiles',
		        'post_status' => array('publish', 'pending'),
		        'meta_key'        => 'profile-lastname',
		        'orderby'        => 'meta_value',
		    	'order'          => 'ASC',
				'posts_per_page' => -1
			);

			// query
			$the_query = new WP_Query( $args );

			if ( $the_query->have_posts() ) : ?>

				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="row">
				
					<?php /* Start the Loop */ 

					while( $the_query->have_posts() ) : $the_query->the_post(); 

						if (get_post_status() == 'publish') {

							include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php');

						}
						else {

							include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive-pending.php');

						}							
					endwhile; 

					?>

				</div> <!-- .row-->

				<?php the_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'page-templates/partials/content', 'none' ); ?>

			<?php endif; 

			wp_reset_query();
			?>

			</div><!-- #content -->
			
		</div><!-- #primary -->

	</div><!-- .columns end -->

</div><!-- .row end -->

<?php get_footer(); ?>
