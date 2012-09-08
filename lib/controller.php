<?php

class Controller {
	public function __call($name, $args) {
		return new PageNotFoundResponse();
	}
}

?>