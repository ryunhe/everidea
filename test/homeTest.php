<?php

require_once ('config/init.php');

class Home_Controller_Test extends PHPUnit_Framework_TestCase {
	protected static $controller;
	public static function setUpBeforeClass() {
		require_once (HTDOCS_PATH . '/controller/home.php');
		self::$controller = new HomeController();
	}

	public function testIndex() {
		$response = self::$controller->indexAction();
		$this->assertInstanceOf('Response', $response);
	}
}

?>