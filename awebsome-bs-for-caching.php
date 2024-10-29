<?php
/*
	Plugin Name: Awebsome Browser Selector for CACHING
	Plugin URI: http://james.revillini.com/awebsome-browser-selector-for-caching/
	Description: An add-on plugin which lets Awebsome! Broswer Selector work on sites that employ caching.
	Version: 1.0.2
	Author: James Revillini <james@revillini.com>
	Author URI: http://james.revillini.com/services/wordpress
	License: GPLv2
*/

class Awebsome_BS_For_Caching {
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	public function __construct() {
		// no need to do anything if ABS is not active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );	// gotta do it (http://codex.wordpress.org/Function_Reference/is_plugin_active)
		if ( !is_plugin_active( 'awebsome-browser-selector/awebsome-browser-selector.php' ) ) return;

		/**
		 * What we're going to do is remove ABS' body class filters because they get generated and then cached, which 
		 * is no good because then they get served to clients who are not using the browsers/platforms that the classes
		 * would suggest. After removing ABS' filters, we enqueue a script to do an AJAX request that fetches the
		 * ABS body classes and applies them dynamically.
		 */


		// remove ABS' filters on the body classes
		add_action( 'init', array( $this, 'remove_filters' ) );
		
		// add browser classes using AJAX calls by enqueing our own script
		add_action( 'init', array( $this, 'enqueue_scripts' ) );

		// both logged in and not logged in users can send this AJAX request. (http://codex.wordpress.org/AJAX_in_Plugins)
		add_action( 'wp_ajax_nopriv_awebsome-bs-for-caching', array( $this, 'get_body_classes' ) ); 
		add_action( 'wp_ajax_awebsome-bs-for-caching', array( $this, 'get_body_classes' ) ); // http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
		
	} // end constructor
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	/**
	 * remove ABS' filters from the body_class hook
	 */
	public function remove_filters ( ) {
	
		// NOTE we cannot use remove_filter to do this because the filter was added by an anonynous object. see http://wordpress.stackexchange.com/questions/57079/how-to-remove-a-filter-that-is-an-anonymous-object
		$this->_remove_anonymous_object_filter( 'body_class', 'Awebsome_Browser_Selector', 'add_body_classes' ); // newer version of ABS
		
	}

	/**
	 * in addition to enqueing our script, we also use a convoluted (but documented) method to pass the ajax URL 
	 * from PHP to javascript (http://codex.wordpress.org/AJAX_in_Plugins)
	 */
	public function enqueue_scripts ( ) {
		
		// use the current requests' protocol in creating url to admin-ajax.php
		$ajaxurl = admin_url( 'admin-ajax.php', $_SERVER['HTTPS'] == 'off' || empty( $_SERVER['HTTPS'] ) ? 'http' : 'https' );
		
		// enqueue our script, which will query back for the body classes we need to apply
		wp_enqueue_script( 'awebsome-bs-for-caching-ajax', plugin_dir_url( __FILE__ ) . 'js/fetch-body-classes.js', array('jquery') );
		
		// create a JS variable AwebsomeBSForCaching which has one property: the admin-ajax.php url
		wp_localize_script( 'awebsome-bs-for-caching-ajax', 'AwebsomeBSForCaching', array( 'ajaxurl' => $ajaxurl ) );
	}

	/**
	 * print the body classes that should get applied for the current client. the value is consumed by an 
	 * AJAX request and appended to the body classes using jQuery.
	 */
	public function get_body_classes ( ) {

		die( Awebsome_Browser_Selector::parse_UA_to_classes( Awebsome_Browser_Selector::parse_UA_string() ) );
		
	}

  
	/*--------------------------------------------*
	 * Private Functions
	 *---------------------------------------------*/
 
	/**
     * Remove an anonymous object filter.
	 * PLEASE STOP USING ANONYMOUS OBJECTS AS WRAPPERS FOR PLUGINS! YEAH RIGHT. WHO IS GOING TO SEE THIS?
     *
     * @param  string $tag    Hook name.
     * @param  string $class  Class name
     * @param  string $method Method name
     * @return void
	 * http://wordpress.stackexchange.com/questions/57079/how-to-remove-a-filter-that-is-an-anonymous-object
     */
	private function _remove_anonymous_object_filter( $tag, $class, $method )
    {
        $filters = $GLOBALS['wp_filter'][ $tag ];

        if ( empty ( $filters ) )
        {
            return;
        }

        foreach ( $filters as $priority => $filter )
        {
            foreach ( $filter as $identifier => $function )
            {
                if ( is_array( $function)
                    and is_a( $function['function'][0], $class )
                    and $method === $function['function'][1]
                )
                {
                    remove_filter(
                        $tag,
                        array ( $function['function'][0], $method ),
                        $priority
                    );
                }
            }
        }
    }
  
} // end class
$awebsome_bs_for_caching = new Awebsome_BS_For_Caching ();