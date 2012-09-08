<?php

class Visitor extends User {
	public function __construct() {
		parent::__construct(isset($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null);
	}

	public static function isAuth() {
		return isset($_SESSION['accessToken']);
	}
}

?>