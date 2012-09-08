<?php

class Request {
	private static $url = null;

	public static function url() {
		return self::$url ?: new Url();
	}

	public static function get($name, $default = null) {
		return self::url()->get($name, $default);
	}

	public static function segment($index = 0, $default = null) {
		return self::url()->segment($index, $default);
	}

	public static function method() {
		return $_SERVER['REQUEST_METHOD']; // GET, POST, PUT or DELETE
	}
}

?>