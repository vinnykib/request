<?php 
/**
 * @package  Request
 */
namespace Includes\Pages\Callbacks;

use Includes\Main\Main;

/**
* 
*/
class SettingsFunctions extends Main
{

    public function requestsOptionsGroup( $input )
	{
		return $input;
	}


	public function checkboxSanitize( $input )
	{
		// return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
		return ( isset($input) ? true : false );
	}

	public function adminSectionManager()
	{
		echo 'Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.';
	}

	public function checkboxField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$checkbox = get_option( $name );
		echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" class="' . $classes . '" name="' . $name . '" value="1" ' . ($checkbox ? 'checked' : '') . '><label for="' . $name . '"></label></div>';
	}

	


    /**
	 * 
	 * Color Start here
	 * 
	*/

}