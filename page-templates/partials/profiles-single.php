<?php
/**
 * Template part for displaying single profiles.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package AUSteve Projects
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<div class="profile-picture">
			<?php $image = get_field('profile-picture'); ?>
			<img src='<?php echo $image['sizes']['medium'] ?>' />
		</div>

		<div class="row profile-mediums">
			<?php $mediums = get_field('profile-mediums'); ?>
			<label>Media:</label>
			<?php 
			$medialist = "";
			
			foreach ($mediums as $medium)
			{
				$medialist .= "<a href='".get_term_link($medium)."'>";
				$medialist .= $medium->name;
				$medialist .= "</a>, ";
			}
			$medialist = substr($medialist, 0, strlen($medialist) - 2);
			
			echo $medialist;
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
