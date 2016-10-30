<?php
/**
 * Template part for displaying single profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Profiles
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<div class="row columns profile-picture">
			<?php $image = get_field('picture'); ?>
			<?php if ($image) { ?>
				<img src='<?php echo $image['sizes']['medium'] ?>'/>
			<?php } else  { ?>
			<!-- Display placeholder image -->
				<img src='<?php echo plugin_dir_url( __FILE__ ).'../../assets/dist/images/profile-placeholder.png'; ?>' alt='Profile picture' height='150px' width='150px'/>
			<?php } ?>
		</div>

		<div class="row columns profile-about">
			<?php $about = get_field('about'); 
			if ($about)
			{
				echo "<em>".$about."</em>";
			}
			?>
		</div>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'austeve-profiles' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php austeve_profiles_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
