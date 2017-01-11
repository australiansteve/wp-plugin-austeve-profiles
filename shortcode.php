<?php
/* Shortcode file */
$filterSet = false;

function austeve_profiles_shortcode_archive(){
	ob_start();

	$meta_query = array('relation' => 'AND');

	//Build name filter
	if( !empty($_GET[ 'search-term' ]) ) {

		$search_term_query = array('relation' => 'OR');

		// append meta query
    	$search_term_query[] = array(
            'key'		=> 'profile-firstname',
            'value'		=> $_GET[ 'search-term' ],
            'compare'	=> 'LIKE',
        );
        // append meta query
    	$search_term_query[] = array(
            'key'		=> 'profile-lastname',
            'value'		=> $_GET[ 'search-term' ],
            'compare'	=> 'LIKE',
        );	
        // append meta query
    	$search_term_query[] = array(
            'key'		=> 'profile-location',
            'value'		=> $_GET[ 'search-term' ],
            'compare'	=> 'LIKE',
        );

    	//Add to the meta_query
        $meta_query[] = $search_term_query;	
        $filterSet = true;
	}

	//Build starts-with filter
	if( !empty($_GET[ 'starts-with' ]) ) {

		$starts_with_query = array('relation' => 'OR');

		// append meta query
    	$starts_with_query[] = array(
            'key'		=> 'profile-firstname',
            'value'		=> '^'.$_GET[ 'starts-with' ],
            'compare'	=> 'REGEXP',
        );
        // append meta query
    	$starts_with_query[] = array(
            'key'		=> 'profile-lastname',
            'value'		=> '^'.$_GET[ 'starts-with' ],
            'compare'	=> 'REGEXP',
        );

    	//Add to the meta_query
        $meta_query[] = $starts_with_query;	
        $filterSet = true;
	}

	//Build profile-type filter
	if( !empty($_GET[ 'profile-type' ]) ) {

		// append meta query
    	$meta_query[] = array(
            'key'		=> 'profile-membership_type',
            'value'		=> $_GET[ 'profile-type' ],
            'compare'	=> '=',
        );
        $filterSet = true;
	}
	//error_log(print_r($meta_query, true));

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'austeve-profiles',
        'post_status' => array('publish'),
        'meta_key'        => 'profile-lastname',
        'orderby'        => 'meta_value',
    	'order'          => 'ASC',
		'posts_per_page' => 10,
		'paged' 		=> $paged,
		'meta_query' => $meta_query
    );
    //var_dump($args);
    $query = new WP_Query( $args );

?>
	<form method="GET" action="#" id="member-filters" onsubmit="return validateSearch()">
		<div class="row">
			<div class="col-sm-12">
				<input id="name-filter" type="text" class="filter" data-filter="search-term" placeholder="Search by name or location" value="<?php echo (isset($_GET['search-term']) ? $_GET['search-term'] : ''); ?>" />
				<input type="submit" value="Search"/>

			</div>

			<div class="col-sm-12 filter-group">
				<select id="name-starts-with-filter" class="filter" data-filter="starts-with" onchange="return validateSearch()">
				<?php
					//Output the empty value
					echo "<option value='' ".((!isset($_GET['starts-with']) || ($_GET['starts-with'] === '')) ? 'selected' : '').">---- Name starting with ----</option>";

					$alphabet = 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z';
					$letters = explode(' ', $alphabet);

					foreach ($letters as $letter)
					{
						echo "<option value='$letter' ".((isset($_GET['starts-with']) && ($_GET['starts-with'] === $letter)) ? 'selected' : '').">$letter</option>";
					}
				?>
				</select>
				<select id="profile-type-filter" class="filter" data-filter="profile-type" onchange="return validateSearch()">
				<?php
					//Output the empty value
					echo "<option value='' ".((!isset($_GET['profile-type']) || ($_GET['profile-type'] === '')) ? 'selected' : '').">---- Membership type ----</option>";

					$profile_types = array('professional'=>'Professional', 
						'student'=>'Emerging Artist', 
						'friend'=>'Friend of the arts', 
						'organization'=>'Organization'
					);

					foreach ($profile_types as $index => $value)
					{
						echo "<option value='$index' ".((isset($_GET['profile-type']) && ($_GET['profile-type'] === $index)) ? 'selected' : '').">$value</option>";
					}
				?>
				</select>
			</div>
			<?php if ($filterSet) { ?>
			<div class="col-sm-12 filter-group">
				<input id="clear-filters" type="submit" onclick="return clearFilters()" value="Clear all filters"/>
			</div>
			<?php } ?>
		</div>
	</form>
<?php
	
    if( $query->have_posts() ){

?>
		<div class="row nav-info">
		  	<div class="col-xs-12">
		  		<em><?php echo $query->found_posts; ?> profiles found. Showing page <?php echo $paged;?> of <?php echo $query->max_num_pages; ?></em>
		  	</div>
	  	</div>
<?php
    	//Navigation before list
		if ($query->max_num_pages > 1) { // check if the max number of pages is greater than 1  
?>
	  	<div class="row navigation">		  	
		  	<div class="col-xs-12">
		  		<nav class="prev-next-posts">
	    			<div class="prev-posts-link page-nav">
		      			<?php echo get_previous_posts_link( '<i class="fa fa-arrow-left" aria-hidden="true"></i> Previous page' ); ?>
		    		</div>
		    		<div class="next-posts-link page-nav">
		      			<?php echo get_next_posts_link( 'Next page <i class="fa fa-arrow-right" aria-hidden="true"></i>', $query->max_num_pages ); ?>
		    		</div>		    
		  		</nav>
		  	</div>
	  	</div>
<?php 
				
		} 

    	echo '<div class="row archive-container">';

		//loop over query results
        while( $query->have_posts() ){
            $query->the_post();
                       
            include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php');		           
        }

    	echo '</div>';

    	//Navigation after list
		if ($query->max_num_pages > 1) { // check if the max number of pages is greater than 1  
?>
	  	<div class="row navigation">
		  	<div class="col-xs-12">
		  		<nav class="prev-next-posts">
		    		<div class="prev-posts-link page-nav">
		      			<?php echo get_previous_posts_link( '<i class="fa fa-arrow-left" aria-hidden="true"></i> Previous page' ); ?>
		    		</div>
		    		<div class="next-posts-link page-nav">
		      			<?php echo get_next_posts_link( 'Next page <i class="fa fa-arrow-right" aria-hidden="true"></i>', $query->max_num_pages ); ?>
		    		</div>		    
		  		</nav>
		  	</div>
	  	</div>
<?php 
				
		} 
    }
    else {
?>
		<div class="row archive-container">
		  	<div class="col-xs-12">
		  		<em>No results found.</em>
		  	</div>
	  	</div>
<?php	
    }

global $wp;
$home_url = home_url();
$current_url = home_url(add_query_arg(array(),$wp->request));
$afterhome = strlen($current_url) - strlen($home_url);
$request_url = substr($current_url, -($afterhome-1));
$paging = strrpos ( $request_url , "/page/" );
if ($paging)
{
	$request_url = substr($request_url, 0, $paging);
}

?>
<script type="text/javascript">

function validateSearch() {

		// vars
		var url = '<?php echo home_url( $request_url ); ?>';
		var args = {};			
		
		jQuery('#member-filters').append('<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');

		// loop over filters
		jQuery('#member-filters .filter').each(function(){
			
			// vars
			var filter = jQuery(this).data('filter'),
				vals = [ jQuery(this).attr('value')];
			
			// append to args
			args[ filter ] = vals.join(',');
			
		});		
		
		// update url
		url += '?';
		
		
		// loop over args
		jQuery.each(args, function( name, value ){			
			url += name + '=' + value + '&';			
		});
		
		
		// remove last &
		url = url.slice(0, -1);
				
		// reload page
		window.location.replace( url );		
		return false;
}

function clearFilters() {

		// vars
		var url = '<?php echo home_url( $request_url ); ?>';		
		
		jQuery('#member-filters').append('<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');
				
		// reload page
		window.location.replace( url );		
		return false;
}

</script>
<?php
    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode( 'member_directory', 'austeve_profiles_shortcode_archive' );

?>
