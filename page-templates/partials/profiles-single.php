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

		<div class="row profile-picture">
			<?php $image = get_field('profile-picture'); ?>
			<img src='<?php echo $image['sizes']['medium'] ?>' />
		</div>

		<div class="row profile-blurb">
			<?php $blurb = get_field('profile-blurb'); 
			if ($blurb)
			{
				echo "<em>".$blurb."</em>";
			}
			?>
		</div>

		<div class="row profile-social">
			<?php $website = get_field('profile-website'); 
			$firstname = get_field('profile-firstname');
			
			if (substr($firstname, strlen($firstname) - 1, 1) === 's') {
				$firstname.='\'';
			}
			else {
				$firstname.='\'s';
			}

			if ($website)
			{
				echo "<a href='".$website."' target='blank' title=\"Visit ".$firstname." website\" alt='Website'><i class='fa fa-2x fa-globe' aria-hidden='true'></i></a>";
			}
			?>
			<?php $facebook = get_field('profile-facebook'); 
			if ($facebook)
			{
				echo "<a href='".$facebook."' target='blank' title=\"Visit ".$firstname." Facebook profile\" alt='Facebook'><i class='fa fa-2x fa-facebook-official' aria-hidden='true'></i></a>";
			}
			?>
			<?php $twitter = get_field('profile-twitter'); 
			if ($twitter)
			{
				echo "<a href='".$twitter."' target='blank' title=\"Visit ".$firstname." Twitter profile\" alt='Twitter'><i class='fa fa-2x fa-twitter' aria-hidden='true'></i></a>";
			}
			?>
			<?php $instagram = get_field('profile-instagram'); 
			if ($instagram)
			{
				echo "<a href='".$instagram."' target='blank' title=\"Visit ".$firstname." Instagram profile\" alt='Instagram'><i class='fa fa-2x fa-instagram' aria-hidden='true'></i></a>";
			}
			?>
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

		<div class="row profile-about">
			<?php $about = get_field('profile-about'); 
			if ($about)
			{
				echo $about;
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
