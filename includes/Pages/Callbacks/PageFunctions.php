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

    public function adminServices()
    {
        return require_once("$this->plugin_path/templates/services.php");
        
    }

    public function adminSettings()
    {
        return require_once("$this->plugin_path/templates/settings.php");
        
    }

}