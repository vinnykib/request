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

	public $colors = array();

	public $filterdays = array();

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
	}
}