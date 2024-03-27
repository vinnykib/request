<?php 
/**
 * @package  Request
 */
namespace Includes\Pages;

use Includes\Main\Main;
use Includes\Menu\AdminMenu;
use Includes\Pages\Callbacks\PageFunctions;

/**
* 
*/
class AdminPages extends Main
{
	public $settings;

	public $callbacks;

	public $pages = array();

	public $subpages = array();



	public function register() 
	{

		$this->settings = new AdminMenu();

		$this->callbacks = new PageFunctions();

		$this->setPages();

		$this->setSubPages();

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
				'page_title' => 'Add request', 
				'menu_title' => 'Add request', 
				'capability' => 'manage_options', 
				'menu_slug' => 'add-request', 
				'callback' => array( $this->callbacks,'addRequest' )
			),
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Services', 
				'menu_title' => 'Services', 
				'capability' => 'manage_options', 
				'menu_slug' => 'edit-tags.php?taxonomy=service_taxonomy'
				// 'callback' => array( $this->callbacks,'adminServices' )
			),
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
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Modify', 
				'menu_title' => 'Modify', 
				'capability' => 'manage_options', 
				'menu_slug' => 'modify', 
				'callback' => array( $this->callbacks,'updateDelete' ),
			),
			array(
				'parent_slug' => 'requests', 
				'page_title' => 'Add request', 
				'menu_title' => 'Add request shortcode', 
				'capability' => 'read', 
				'menu_slug' => 'add-request-shortcode', 
				'callback' => array( $this->callbacks,'addRequestShortcode' )
			)
		);
	}

}