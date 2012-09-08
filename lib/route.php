<?php

require_once ('lib/response.php');

class Route {
	public static $modules = array(
		'api' => 1,
		);
	
	public static function dispatch() {
		$response = self::getModule()->{self::getAction()}();
		$response->send();
	}

	public static function getModule() {
		$module = strtolower(Request::segment(0, 'home'));
		if (isset(self::$modules[$module])) {
			include_once ("module/{$module}.php");
			return new $module;
		} else {
			$_filename = "controller/{$module}.php";
			if (file_exists($_filename)) {
				include_once ($_filename);
				$controller = "{$module}Controller";
				return new $controller;
			}
			return new Controller;
		}
	}

	public static function getAction() {
		return strtolower(Request::segment(1, 'index')) . 'Action';
	}
}

?>