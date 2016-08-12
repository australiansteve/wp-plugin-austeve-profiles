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

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="row">
				<div id="archive-filters">
<?php foreach( $GLOBALS['my_query_filters'] as $key => $name ): 
	
	// get the field's settings without attempting to load a value
	$field = get_field_object($key, false, false);
	
	
	// set value if available
	if( isset($_GET[ $name ]) ) {
		
		$field['value'] = explode(',', $_GET[ $name ]);
		
	}
	
	
	// create filter
	?>
	<div class="filter" data-filter="<?php echo $name; ?>">
		<?php create_field( $field ); ?>
	</div>
	
<?php endforeach; ?>
</div>

<script type="text/javascript">
(function($) {
	
	// change
	$('#archive-filters').on('change', 'input[type="checkbox"]', function(){

		// vars
		var url = '<?php echo home_url('property'); ?>';
			args = {};
			
		
		// loop over filters
		$('#archive-filters .filter').each(function(){
			
			// vars
			var filter = $(this).data('filter'),
				vals = [];
			
			
			// find checked inputs
			$(this).find('input:checked').each(function(){
	
				vals.push( $(this).val() );
	
			});
			
		
			// append to args
			args[ filter ] = vals.join(',');
			
		});
				
		// update url
		url += '?';
				
		// loop over args
		$.each(args, function( name, value ){
			
			url += name + '=' + value + '&';
			
		});
				
		// remove last &
		url = url.slice(0, -1);
			
		// reload page
		window.location.replace( url );
	});
})(jQuery);
</script>
				</div>
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
