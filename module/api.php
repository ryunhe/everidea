<?php

class Api {
	public function __call($name, $args) {
		$name = Request::segment(1);
		$class = "Endpoints\\{$name}";
		$endpoint = new $class;
		return $endpoint->call(Request::segment($name));
	}
}

class ApiResponse extends Response {
	public function send() {
		echo json_encode(array(
			"method" => \Request::method(),
			"status" => $this->status,
			"result" => $this->result,
			));
	}
}

class ApiMissParameterResponse extends ApiResponse {
	public function __construct() {
		$this->result = "Required parameter missing.";
		$this->status = self::STATUS_BAD_REQUEST;
	}
}

class ApiAccessDeniedResponse extends ApiResponse {
	public function __construct() {
		$this->result = "Access denied.";
		$this->status = self::STATUS_BAD_REQUEST;
	}
}


?>