<?php
/**
* Plugin Name: Implementing WordPress Nonces
* Plugin URI: http://www.sitepoint.com
* Version: 1.0
* Author: n7 Studios
* Author URI: http://www.n7studios.co.uk
* Description: Example Plugin demonstrating how to implement WordPress Nonces
* License: GPL2
*/

class ImplementingWPNonces {

	function __construct() {

		// Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name         = 'implementing-wordpress-nonces'; // Plugin Folder
        $this->plugin->displayName  = 'Nonces'; // Plugin Name

		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts_css' ) );
		add_action( 'wp_ajax_implementing_wp_nonces', array( &$this, 'admin_ajax_save' )  );

	}
	
	/**
    * Register the plugin settings panel
    *
    * @since 1.0.0
    */
    function admin_menu() {

        add_menu_page( $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array( &$this, 'admin_screen' ), 'dashicons-admin-network' );

    }

	/**
	* Register and enqueue any JS and CSS for the WordPress Administration
	*
	* @since 1.0.0
	*/
	function admin_scripts_css() {

		// JS
		wp_enqueue_script( $this->plugin->name, plugin_dir_url( __FILE__ ) . 'admin.js', array( 'jquery' ), '1.0', true );
		wp_localize_script( $this->plugin->name, 'implementing_wordpress_nonces', array(
			'nonce' => wp_create_nonce( 'implementing_wordpress_nonces_ajax_save' ),
		) );
		
	}

    /**
    * Output the Administration Screens
    * Save POSTed data from the Administration Panel into a WordPress option
    *
    * @since 1.0.0
    */
    function admin_screen() {

        // Save Settings
        if ( isset( $_REQUEST['implementing_wordpress_nonces'] ) ) {
        	if ( isset( $_REQUEST[ 'implementing_wordpress_nonces_nonce' ] ) && wp_verify_nonce( $_REQUEST[ 'implementing_wordpress_nonces_nonce' ], 'implementing_wordpress_nonces_save' ) ) {
	        	update_option( 'implementing_wordpress_nonces', sanitize_text_field( $_REQUEST[ 'implementing_wordpress_nonces' ] ) );
	        	$message = __( 'Settings saved', $this->plugin->name );
	        } else {
	        	// Nonce could not be verified - bail
	        	wp_die( __( 'Invalid nonce specified', $this->plugin->name ), __( 'Error', $this->plugin->name ), array(
	        		'response' 	=> 403,
	        		'back_link' => 'admin.php?page=' . $this->plugin->name,
	        	) );
        	}
        }

        // Output form
        ?>
        <div class="wrap">
		    <h2><?php echo $this->plugin->displayName; ?></h2>  
		    <?php    
		    if ( isset( $message ) ) {
		        ?>
		        <div class="updated fade"><p><?php echo $message; ?></p></div>  
		        <?php
		    }
		    ?>
	        <form id="implementing-wordpress-nonces" name="post" method="post" action="admin.php?page=<?php echo $this->plugin->name; ?>">
	        	<div>
	        		<label for="implementing_wordpress_nonces"><?php _e( 'Enter any value', $this->plugin->name ); ?></label>
					<input type="text" id="implementing_wordpress_nonces" name="implementing_wordpress_nonces" value="<?php echo get_option( 'implementing_wordpress_nonces' ); ?>" />
			    	<?php wp_nonce_field( 'implementing_wordpress_nonces_save', 'implementing_wordpress_nonces_nonce' ); ?>
			    	<input type="submit" name="submit" value="<?php _e( 'Save', $this->plugin->name ); ?>" class="button button-primary" />            
	        	</div>
	        </form>
        </div>
        <?php
    }

	/**
	 * Saves POSTed settings data
	 *
	 * @since 1.0.0
	 */
	function admin_ajax_save() {

		// Run a security check first.
    	check_ajax_referer( 'implementing_wordpress_nonces_ajax_save', 'nonce' );

		// Save option and return 1
		update_option( 'implementing_wordpress_nonces', sanitize_text_field( $_REQUEST[ 'implementing_wordpress_nonces' ] ) );
		echo 1;
		die();

	}

}

$implementing_wordpress_nonces = new ImplementingWPNonces;