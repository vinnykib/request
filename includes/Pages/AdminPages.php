<?php 
/**
 * @package  Request
 */
namespace Includes\Pages;

use Includes\Main\Main;
use Includes\Menu\AdminMenu;
use Includes\Pages\Callbacks\PageFunctions;
use Includes\Pages\Callbacks\SettingsFunctions;

/**
* 
*/
class AdminPages extends Main
{
	public $settings;

	public $callbacks;

	public $settings_callbacks;

	public $pages = array();

	public $subpages = array();



	public function register() 
	{

		$this->settings = new AdminMenu();

		$this->callbacks = new PageFunctions();

		$this->settings_callbacks = new SettingsFunctions();

		$this->setPages();

		$this->setSubPages();

		$this->setSettings();

		$this->setSections();

		$this->setFields();

		// $this->setColorSettings();

		// $this->setColorSections();

		// $this->setColorFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Requests List' )->addSubPages( $this->subpages )->register();
	}

	public function setPages()
	{
		$this->pages = array(
			array(
				'page_title' => 'Requests plugin', 
				'menu_title' => 'Requests', 
				'capability' => 'manage_options', 
				'menu_slug' => 'requests', 
				'callback' => array( $this->callbacks,'adminRequest' ), 
				'icon_url' => 'dashicons-store', 
				'position' => 110
			)
		);
	}

	public function setSubPages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Pending', 
				'menu_title' => 'Pending', 
				'capability' => 'manage_options', 
				'menu_slug' => 'pending', 
				'callback' => array( $this->callbacks,'pending' )
			),
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Add request', 
				'menu_title' => 'Add request', 
				'capability' => 'manage_options', 
				'menu_slug' => 'add-request', 
				'callback' => array( $this->callbacks,'addRequest' )
			),
			// array(
			// 	'parent_slug' => 'requests', 
			// 	'page_title' => 'Services', 
			// 	'menu_title' => 'Services', 
			// 	'capability' => 'manage_options', 
			// 	'menu_slug' => 'edit-tags.php?taxonomy=service_taxonomy'
			// 	// 'callback' => array( $this->callbacks,'adminServices' )
			// ),
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Customers', 
				'menu_title' => 'Customers', 
				'capability' => 'manage_options', 
				'menu_slug' => 'users.php?role=request_customer'
				// 'callback' => array( $this->callbacks,'adminCustomers' )
			),
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Settings', 
				'menu_title' => 'Settings', 
				'capability' => 'manage_options', 
				'menu_slug' => 'settings', 
				'callback' => array( $this->callbacks,'adminSettings' ),
			),
		);
	}

	public function setSettings()
{
    $args = array();

    // Prepare settings for weeks
    foreach ($this->weeks as $key => $value) {
        $args[] = array(
            'option_group' => 'requests_options_group',
            'option_name' => $key,
            'callback' => array($this->settings_callbacks, 'checkboxSanitize')
        );
    }

	// Prepare settings for filter days
    foreach ($this->filterdays as $key => $value) {
        $args[] = array(
            'option_group' => 'requests_options_group',
            'option_name' => $key,
            'callback' => array($this->settings_callbacks, 'filterSanitize')
        );
    }

    // Register all settings
    foreach ($args as $arg) {
        register_setting($arg['option_group'], $arg['option_name'], $arg['callback']);
    }

    // Apply the settings
    $this->settings->setSettings($args);
}

	
public function setSections()
{
    $args = array(
        array(
            'id' => 'request_admin_index',
            'title' => 'Days of the week requests allowed',
            'callback' => array($this->settings_callbacks, 'adminSectionManager'),
            'page' => 'settings'
		),
		array(
            'id' => 'filterdays_admin_index',
            'title' => 'Set start and end date for requests',
            'callback' => array($this->settings_callbacks, 'filterSectionManager'),
            'page' => 'settings'
        )
    );

    $this->settings->setSections($args);
}

public function setFields()
{
    $args = array();

    // Prepare fields for weeks
    foreach ($this->weeks as $key => $value) {
        $args[] = array(
            'id' => $key,
            'title' => $value,
            'callback' => array($this->settings_callbacks, 'checkboxField'),
            'page' => 'settings',
            'section' => 'request_admin_index',
            'args' => array(
                'label_for' => $key,
                'class' => 'day-settings'
            )
        );

		
    }

	 // Prepare fields for filterdays
	 foreach ($this->filterdays as $key => $value) {
        $args[] = array(
            'id' => $key,
            'title' => $value,
            'callback' => array($this->settings_callbacks, 'filterCheckboxField'),
            'page' => 'settings',
            'section' => 'filterdays_admin_index',
            'args' => array(
                'label_for' => $key,
                'class' => 'filter-settings'
            )
        );
    }

    // Set the fields
    $this->settings->setFields($args);
}


}