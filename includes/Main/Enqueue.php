<?php 
/**
 * @package  Request
 */
namespace Includes\Main;

use \Includes\Main\Main;

/**
* 
*/
class Enqueue extends Main
{
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	
	public function enqueue() {
		// Enqueue the WordPress color picker style and script
        wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
      
		// Enqueue jquery
		wp_enqueue_script( 'jquery' ); 

		// Enqueue plugin styles
		wp_enqueue_style( 'rqtpluginstyle', $this->plugin_url . 'assets/css/css.css' );

		// Enqueue all plugin scripts
		wp_enqueue_script( 'rqtcalendarscript', $this->plugin_url . 'assets/js/calendar.js' );
		wp_enqueue_script( 'rqtajaxcript', $this->plugin_url . 'assets/js/crud-ajax.js' );
		wp_enqueue_script( 'rqtpluginscript', $this->plugin_url . 'assets/js/js.js', array('jquery', 'wp-color-picker') );


			$header = get_option('colors', '#000000');
			$custom_color = "
			#header {
					background-color: {$header};
				}
			";
			wp_register_style('custom-color-changer', false);
			wp_enqueue_style('custom-color-changer');

	}
}
