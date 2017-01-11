<?php
/* Shortcode file */

function austeve_profiles_shortcode_archive(){
	ob_start();

	$meta_query = array('relation' => 'OR');

	//Build name filter
	if( !empty($_GET[ 'search-term' ]) ) {			
		// append meta query
    	$meta_query[] = array(
            'key'		=> 'firstname',
            'value'		=> $_GET[ 'search-term' ],
            'compare'	=> 'LIKE',
        );
        // append meta query
    	$meta_query[] = array(
            'key'		=> 'lastname',
            'value'		=> $_GET[ 'search-term' ],
            'compare'	=> 'LIKE',
        );
	}

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'austeve-profiles',
        'post_status' => array('publish'),
        'meta_key'        => 'lastname',
        'orderby'        => 'meta_value',
    	'order'          => 'ASC',
		'posts_per_page' => 10,
		'paged' 		=> $paged,
		'meta_query' => $meta_query
    );
    //var_dump($args);
    $query = new WP_Query( $args );

?>
	<div class="row">
		<div class="small-12 columns">
			<form method="GET" action="#" id="member-filters" onsubmit="return validateSearch()">
				<input id="name-filter" type="text" class="filter" data-filter="search-term" placeholder="Search by name" value="<?php echo (isset($_GET['search-term']) ? $_GET['search-term'] : ''); ?>" />
				<input type="submit" value="Search"/>
			</form>
		</div>
	</div>
<?php
	
    if( $query->have_posts() ){

?>
		<div class="row nav-info">
		  	<div class="columns small-12">
		  		<em><?php echo $query->found_posts; ?> profiles found. Showing page <?php echo $paged;?> of <?php echo $query->max_num_pages; ?></em>
		  	</div>
	  	</div>
<?php
    	//Navigation before list
		if ($query->max_num_pages > 1) { // check if the max number of pages is greater than 1  
?>
	  	<div class="row navigation">		  	
		  	<div class="columns small-12">
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

    	echo '<div class="row columns archive-container">';

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
		  	<div class="columns small-12">
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
		  	<div class="columns small-12">
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
		var url = "<?php echo home_url( $request_url ); ?>";
		var args = {};			
		
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
</script>
<?php
    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode( 'profile_list', 'austeve_profiles_shortcode_archive' );

?>
