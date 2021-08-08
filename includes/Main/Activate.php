<?php
/**
 * @package  Request
 */
namespace Includes\Main;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}