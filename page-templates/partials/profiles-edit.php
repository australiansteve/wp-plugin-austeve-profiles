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

	<div class="entry-content edit-profile">

		<div class="row profile-picture">
			<?php $image = get_field('profile-picture'); ?>
			<img src='<?php echo $image['sizes']['medium'] ?>' />
		</div>

		<div class="row edit-profile-blurb">
			<?php 
			$field_name = "profile-blurb";
			$field = get_field_object($field_name);

			echo "<label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions'];
			echo "<textarea rows='2' value='".$field['value']."' >".$field['value']."</textarea>";			
			?>
		</div>

		<div class="row edit-profile-social">
			<?php 

			//Website
			$field_name = "profile-website";
			$field = get_field_object($field_name);

			echo "<p><label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions']."</em>";
			echo "<input value='".$field['value']."' ></input>";			

			//Facebook
			$field_name = "profile-facebook";
			$field = get_field_object($field_name);

			echo "<p><label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions']."</em>";
			echo "<input value='".$field['value']."' ></input>";			

			//Twitter
			$field_name = "profile-twitter";
			$field = get_field_object($field_name);

			echo "<p><label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions']."</em>";
			echo "<input value='".$field['value']."' ></input>";			

			//Instagram
			$field_name = "profile-instagram";
			$field = get_field_object($field_name);

			echo "<p><label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions']."</em>";
			echo "<input value='".$field['value']."' ></input>";			

			//Tumblr
			$field_name = "profile-tumblr";
			$field = get_field_object($field_name);

			echo "<p><label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions']."</em>";
			echo "<input value='".$field['value']."' ></input>";			

			?>
		</div>

		<div class="row edit-profile-location">
			<?php
			//Location
			$field_name = "profile-location";
			$field = get_field_object($field_name);

			echo "<label>".$field['label'].": </label>";
			echo "<em> ".$field['instructions'];
			echo "<input value='".$field['value']."' ></input>";			
			?>
		</div>

		<div class="row edit-profile-mediums">
			<?php 
			$field = get_field_object('profile-mediums');
			$values = $field['value'];
			?>
			<label>Media:</label>
			<?php 

			//First output the mediums that the user has already selected
			if ($values) 
			{
				foreach ($values as $medium)
				{
					echo "<input type='checkbox' name='profile-mediums' value='".$medium->term_id."' checked='checked'/> ".$medium->name."<br/>";
				}
			}

			//Then output all other mediums
			$terms = get_terms( array(
			    'taxonomy' => 'austeve_mediums',
			    'hide_empty' => false,
			) );

			foreach ($terms as $term) {
				if (!in_array($term, $values))
				{
					echo "<input type='checkbox' name='profile-mediums' value='".$term->term_id."'/>".$term->name."<br/>";
				}
			}

			echo "<input type='checkbox' name='profile-mediums' > Other (please specify): <input type='text' name='profile-mediums-other'/>";
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
