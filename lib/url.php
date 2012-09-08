<?php

class Url {
	private $segments = array();

	public function __construct($path = null) {
		$pathname = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		$this->segments = array_values(array_filter(explode('/', $path ?: parse_url($pathname, PHP_URL_PATH))));
		$this->parameters = array_merge($_GET, $_POST);
	}

	public function get($name, $default = null) {
		return $this->wash(isset($this->parameters[$name]) ? $this->parameters[$name] : $default);
	}

	public function segment($index = 0, $default = null) {
		if ($index < 0)
			$index = count($this->segments) + $index;
		elseif (!is_int($index))
			if (in_array($index, $this->segments))
				$index = array_search($index, $this->segments) + 1;

		return $this->wash(isset($this->segments[$index]) ? $this->segments[$index] : $default);
	}

	protected function wash($value) {
		if (is_string($value))
			$value = trim($value);
		else if (is_array($value) && count($value))
			foreach ($value as $key => &$val)
				$val = self::wash($val);
		return $value;
	}
}

?>