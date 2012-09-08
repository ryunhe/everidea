<?php

class Response {
	const STATUS_OK = 200;
	const STATUS_MOVED = 301; // Moved Permanently
	const STATUS_FOUND = 302;
	const STATUS_NOT_MODIFIED = 304;
	const STATUS_BAD_REQUEST = 400;
	const STSTUS_UNAUTHORIZED = 401;
	const STSTUS_FORBIDDEN = 403;
	const STATUS_NOT_FOUND = 404;
	const STATUS_ERROR = 500; // Internal Server Error
	public $result;
	public $status;
	public function __construct($result, $status = self::STATUS_OK) {
		$this->result = $result;
		$this->status = $status;
	}

	public function send() {
		echo strval($this->result);
	}
}

class RedirectResponse extends Response {
	protected $definitions = array(
		'301' => 'Moved Permanently',
		'302' => 'Found',
		);

	public function send() {
		$status = $this->status == self::STATUS_MOVED ? self::STATUS_MOVED : self::STATUS_FOUND;
		header("HTTP/1.1 {$status} {$definitions[$status]}");
		header("Location: {$this->result}");
		echo "<a href=\"{$this->result}\">Redirecting..</a>";
	}
}

class PageNotFoundResponse extends Response {
	public function __construct() {
		$this->result = "Page not found.";
		$this->status = self::STATUS_NOT_FOUND;
	}
}

class FatalErrorResponse extends Response {
	public function __construct($message = null) {
		$this->result = $message ?: "Internal Server Error.";
		$this->status = self::STATUS_ERROR;
	}
}

?>