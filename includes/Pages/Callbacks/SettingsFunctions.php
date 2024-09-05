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
	 * Filter dates Start here
	 * 
	*/

	public function filterSanitize( $input )
	{
		return $input;
	}

	public function filterSectionManager()
	{
		echo 'Manage the Sections and Features of this Plugin';
	}

	public function filterCheckboxField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$value = get_option( $name );
		echo '<div class="' . $classes . '"><input type="date" id="' . $name . '" class="' . $classes . '" name="' . $name . '" value="'.$value.'"><label for="' . $name . '"></label></div>';
	}

    /**
	 * 
	 * Allowed time starts here
	 * 
	*/

	public function allowedTimeSanitize( $input )
	{
		return $input;
	}

	public function allowedTimeSectionManager()
	{
		echo 'Manage the Sections and Features of this Plugin';
	}

	public function allowedTimeCheckboxField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$value = get_option( $name );
		echo '<div class="' . $classes . '"><input type="time" id="' . $name . '" class="' . $classes . '" name="' . $name . '" value="'.$value.'"><label for="' . $name . '"></label></div>';
	}


   /**
	 * 
	 * Buffer time starts here
	 * 
	*/

	public function bufferTimeSanitize( $input )
	{
		return $input;
	}

	public function bufferTimeSectionManager()
	{
		echo 'Manage the Sections and Features of this Plugin';
	}

	public function bufferTimeCheckboxField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$value = get_option( $name, 0 ); // Default to 0 if not set
		
		echo '
		<div class="' . $classes . '">
			<label for="counter">Hours: <span id="rangeValue">' . $value . '</span></label>
			<input type="range" min="0" max="24" id="' . $name . '" class="' . $classes . '" name="' . $name . '" value="' . $value . '" step="1" oninput="document.getElementById(\'rangeValue\').innerText = this.value;">
		</div>';
	}


	/**
	 * 
	 * Emails subjects starts here
	 * 
	*/

	public function subjectsSanitize( $input )
	{
		return $input;
	}

	public function subjectsSectionManager()
	{
		echo 'Manage the Sections and Features of this Plugin';
	}

	public function subjectsField( $args ) {
		$name = $args['label_for'];
		$classes = $args['class'];
		$value = get_option( $name, '' ); // Default to empty string if not set
		
		echo '
		<div class="' . esc_attr($classes) . '">
			<input type="text" id="' . esc_attr($name) . '" class="' . esc_attr($classes) . '" name="' . esc_attr($name) . '" value="' . esc_attr( $value ) . '" />
		</div>';
	}
	
	

	/**
	 * 
	 * Emails starts here
	 * 
	*/

	public function emailsSanitize( $input )
	{
		return $input;
	}

	public function emailsSectionManager()
	{
		echo 'Manage the Sections and Features of this Plugin';
	}

public function emailsField( $args ) {
    $name = $args['label_for'];
    $classes = $args['class'];
    $value = get_option($name, ''); // Default to empty string if not set
    $field_type = $args['field_type'];

    if ($field_type === 'textarea') {
        echo '
        <div class="' . esc_attr($classes) . '">
            <textarea id="' . esc_attr($name) . '" class="' . esc_attr($classes) . '" name="' . esc_attr($name) . '" rows="5" cols="50">' . esc_textarea($value) . '</textarea>
        </div>';
    } else { // input field
        echo '
        <div class="' . esc_attr($classes) . '">
            <input type="text" id="' . esc_attr($name) . '" class="' . esc_attr($classes) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" />
        </div>';
    }
}


	



}