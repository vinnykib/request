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
	
	function enqueue() {
		// enqueue all our scripts
		wp_enqueue_style( 'rqtpluginstyle', $this->plugin_url . 'assets/css/css.css' );
		wp_enqueue_script( 'rqtpluginscript', $this->plugin_url . 'assets/js/js.js' );
	}
}
