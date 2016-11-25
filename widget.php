<?php
// Creating the widget 
class austeve_profiles_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
        'austeve_profiles_widget', 

        // Widget name will appear in UI
        __('AUSteve Profile Widget', 'austeve_profiles_widget_domain'), 

        // Widget description
        array( 'description' => __( 'Display links to a Dipster profile', 'austeve_profiles_widget_domain' ), ) 
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {

    	$widgetOutput = "<div class='widget'>";

		//New query to get current user profile
		if (current_user_can('mepr-active','rule: '.MEPR_REGISTERED_RULE)) {

    		$widgetOutput .= "<div class='widget-inner'>";
    		$widgetOutput .= "<h1 class='widget-title m-has-ico'>";
        	$widgetOutput .= apply_filters( 'widget_title', $instance['title'] );

            if ( isset($instance['helppage']) && $instance['helppage'] != '' )
            {
                $widgetOutput .= "<span style='float:right'><a href='".get_permalink($instance['helppage'])."' target='blank' title='Profile help'>";
                $widgetOutput .= "<i class='fa fa-question-circle' aria-hidden='true'></i></a></span>";
            }

            $widgetOutput .= "</h1>";

		    $current_user = wp_get_current_user();

			// args
			$args = array(
				'numberposts'	=> 1,
				'post_type'		=> 'austeve-profiles',
				'post_status'	=> array ('publish'),
				'meta_key'		=> 'user',
				'meta_value'	=> ''.$current_user->ID
			);

			// query
			$the_query = new WP_Query( $args );

			if( $the_query->have_posts() ): 
				while( $the_query->have_posts() ) : $the_query->the_post();
                    $widgetOutput .= "<p>Tell us about yourself and set a fundraising goal</p>";
                    $widgetOutput .= "<p><a class='button' href='".get_permalink($instance['editprofilepage'])."'>";
                    $widgetOutput .= "Edit my profile</a>";
					$widgetOutput .= "<p>Go to your fundraising page</p>";
                    $widgetOutput .= "<p><a class='button' href='".get_permalink()."' target='_blank'>";
					$widgetOutput .= "View my profile</a>";
				endwhile;
			else:
				if ( isset($instance['editprofilepage']))
				{
					$widgetOutput .= "<p><a class='button' href='".get_permalink($instance['editprofilepage'])."'>";
					$widgetOutput .= "Create a profile!</a>";
				}
			endif;

			wp_reset_query();	 // Restore global post data stomped by the_post().

    		$widgetOutput .= "</div>";
    	}
        else {

            //Not registered
            $widgetOutput .= "<div class='widget-inner'>";
            $widgetOutput .= "<h1 class='widget-title m-has-ico'>";
            $widgetOutput .= "Register";
            $widgetOutput .= "</h1>";
            $widgetOutput .= "<p>Register for this year's event</p>";
            $widgetOutput .= "<p><a class='button' href='".get_permalink($instance['registerpage'])."'>";
            $widgetOutput .= "Register Now</a>";

        }

    	$widgetOutput .= "</div>";
    	echo $widgetOutput;
	}
        
    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Profile', 'austeve_profiles_widget_domain' );
        }
        if ( isset( $instance[ 'editprofilepage' ] ) ) {
            $editprofilepage = $instance[ 'editprofilepage' ];
        }
        else {
            $editprofilepage = __( 'Edit profile page', 'austeve_profiles_widget_domain' );
        }
        if ( isset( $instance[ 'helppage' ] ) ) {
            $helppage = $instance[ 'helppage' ];
        }
        else {
            $helppage = __( 'Help page', 'austeve_profiles_widget_domain' );
        }

        // Widget admin form
?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'editprofilepage' ); ?>"><?php _e( 'Edit Profile page: ' ); ?></label> 
        <?php
            wp_dropdown_pages(array(
            'id' => $this->get_field_id('editprofilepage'),
            'name' => $this->get_field_name('editprofilepage'),
            'selected' => isset($instance['editprofilepage']) ? $instance['editprofilepage'] : '',
            'show_option_none' => 'Select page...',
            'option_none_value' => ''
        ));
        ?>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'registerpage' ); ?>"><?php _e( 'Registration page: ' ); ?></label> 
        <?php
            wp_dropdown_pages(array(
            'id' => $this->get_field_id('registerpage'),
            'name' => $this->get_field_name('registerpage'),
            'selected' => isset($instance['registerpage']) ? $instance['registerpage'] : '',
            'show_option_none' => 'Select page...',
            'option_none_value' => ''
        ));
        ?>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'helppage' ); ?>"><?php _e( 'Help page: ' ); ?></label> 
        <?php
            wp_dropdown_pages(array(
            'id' => $this->get_field_id('helppage'),
            'name' => $this->get_field_name('helppage'),
            'selected' => isset($instance['helppage']) ? $instance['helppage'] : '',
            'show_option_none' => 'Select page...',
            'option_none_value' => ''
        ));
        ?>
        </p>
<?php 
    }
        
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['editprofilepage'] = ( ! empty( $new_instance['editprofilepage'] ) ) ? strip_tags( $new_instance['editprofilepage'] ) : '';
        $instance['registerpage'] = ( ! empty( $new_instance['registerpage'] ) ) ? strip_tags( $new_instance['registerpage'] ) : '';
        $instance['helppage'] = ( ! empty( $new_instance['helppage'] ) ) ? strip_tags( $new_instance['helppage'] ) : '';

        update_option("austeve_profile_help_page", $instance['helppage']);
        return $instance;
    }
} // Class austeve_profiles_widget ends here


// Register and load the widget itself
function austeve_profiles_load_widget() {
    register_widget( 'austeve_profiles_widget' );

}
add_action( 'widgets_init', 'austeve_profiles_load_widget' );

?>