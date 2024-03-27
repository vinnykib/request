<?php 
/**
 * @package  Request
 */
namespace Includes\Pages\Callbacks;

use Includes\Main\Main;

/**
* 
*/
class PageFunctions extends Main
{

    public function adminRequest()
    {
        return require_once("$this->plugin_path/templates/requests.php");
        
    }
    public function addrequest()
    {
        return require_once("$this->plugin_path/templates/add-request.php");
        
    }

    public function adminServices()
    {
        return require_once("$this->plugin_path/templates/services.php");
        
    }

    public function adminCustomers()
    {
        return require_once("$this->plugin_path/templates/customers.php");
        
    }

    public function adminSettings()
    {
        return require_once("$this->plugin_path/templates/settings.php");
        
    }
    public function updateDelete()
    {
        return require_once("$this->plugin_path/templates/update-delete.php");
        
    }
    public function addRequestShortcode()
    {
        return require_once("$this->plugin_path/shortcodes/add-request.php");
        
    }

}