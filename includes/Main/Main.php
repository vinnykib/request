<?php 
/**
 * @package  Request
 */
namespace Includes\Main;

class Main
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public $weeks = array();

	public $filterdays = array();

	public $allowedtime = array();

	public $buffertime = array();

	public $emails = array();

	public $extrafields = array();

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/request.php';

		$this->weeks = array(
			'sunday' => 'Sunday',
			'monday' => 'Monday',
			'tuesday' => 'Tuesday',
			'wednesday' => 'Wednesday',
			'thursday' => 'Thursday',
			'friday' => 'Friday',
			'saturday' => 'Saturday'
		);

		$this->filterdays = array(
			'start_day' => 'Start day',
			'end_day' => 'End day'
			
		);

		$this->allowedtime = array(
			'start_time' => 'Start time',
			'end_time' => 'End time'
			
		);

		$this->buffertime = array(
			'allowed_buffer_time' => 'Allowed Buffer Time'
			
		);

		$this->emails = array(
			'create_new_request_email_input' => 'Email Subject',
			'create_new_request_email_textarea' => 'Request Email for new user',
			'create_request_email_input' => 'Email Subject',
			'create_request_email_textarea' => 'Request Email for existing user',
			'approve_email_input' => 'Email Subject',
			'approve_email_textarea' => 'Approve Email',
			'cancel_email_input' => 'Email Subject',
			'cancel_email_textarea' => 'Cancel Email'
			
		);

		$this->extrafields = array(
			'setting_phone_field' => 'Disable Phone field',
			'is_phone_required' => 'Make Phone field required',
			'setting_description_field' => 'Disable Description field',
			'is_description_required' => 'Make Description area required'			
		);
	}
}
