<?php
/**
 * @package  Request
 */
namespace Includes\Main;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}