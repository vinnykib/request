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
        return require_once("$this->plugin_path/templates/request-list.php");
        
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

    public function pending()
    {
        return require_once("$this->plugin_path/templates/pending.php");
        
    }


}