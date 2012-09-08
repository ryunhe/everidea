<?php

class View {
	protected static $global = array();
	protected $data = array();
	protected $name;
	private $render;

	const PRINT_OUTPUT = true;

	public function __construct($name, array $data = array()) {
		$this->name = $name;
		$this->data = $data;
	}

	public function render($print = false) {
		ob_start();

		extract(array_merge(self::$global, $this->data));
		include (HTDOCS_PATH . "/view/{$this->name}.php");

		$this->render = ob_get_clean();

		return $print ? print $this->render : $this->render;
	}

	public function set($name, $value = null) {
		if (func_num_args() === 1 && is_array($name)) {
			foreach ($name as $key => $value) {
				$this->__set($key, $value);
			}
		} else {
			$this->__set($name, $value);
		}
		
		return $this;
	}

	public function __set($key, $value) {
		if (!isset($this->$key)) {
			$this->data[$key] = $value;
		}
	}

	public function __get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	public function __toString() {
		return $this->render();
	}
}
