<?php
/* Shortcode file */

// array of filters (field key => field name)
$GLOBALS['my_query_filters'] = array( 
	'First Name'	=> 'profile-firstname', 
	'Last Name'	=> 'profile-lastname'
);

function austeve_profiles_shortcode_archive(){
	ob_start();

	$meta_query = array('relation' => 'OR');

	//Build name filter
	if( !empty($_GET[ 'member-name' ]) ) {			
		// append meta query
    	$meta_query[] = array(
            'key'		=> 'profile-firstname',
            'value'		=> $_GET[ 'member-name' ],
            'compare'	=> 'LIKE',
        );
        // append meta query
    	$meta_query[] = array(
            'key'		=> 'profile-lastname',
            'value'		=> $_GET[ 'member-name' ],
            'compare'	=> 'LIKE',
        );		
	}

	//var_dump($meta_query);

    $args = array(
        'post_type' => 'austeve-profiles',
        'post_status' => array('publish'),
        'meta_key'        => 'profile-lastname',
        'orderby'        => 'meta_value',
    	'order'          => 'ASC',
		'posts_per_page' => 20,
		'meta_query' => $meta_query
    );
    $query = new WP_Query( $args );

?>
	<div class="row">
		<div class="col-sm-12">
			<form method="GET" action="?profile-name=y" id="member-filters" onsubmit="return validateSearch()">
				<input type="text" class="filter" data-filter="member-name" placeholder="Search by name" value="<?php echo (isset($_GET['member-name']) ? $_GET['member-name'] : ''); ?>" />
				<input type="submit" value="Search"/>
			</form>
		</div>
	</div>
<?php
    echo '<div class="row archive-container">';
	
    if( $query->have_posts() ){
        while( $query->have_posts() ){
            $query->the_post();
                       
            include( plugin_dir_path( __FILE__ ) . 'page-templates/partials/profiles-archive.php');		           
        }
    }
    echo '</div>';
?>
<script type="text/javascript">

function validateSearch() {

		// vars
		var url = '<?php echo home_url('members'); ?>';
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

add_shortcode( 'member_directory', 'austeve_profiles_shortcode_archive' );

?>
