<?php

class Cache {
	protected static $instance;
	protected $caching = true;
	protected $prefix;
	protected $key;
	public function __constructor($prefix, $caching = true) {
		if (!$caching)
			$this->caching = false;

		$this->prefix = $prefix;
	}

	public function key($key) {
		if (!empty($key))
			$this->key = $key;

		if (!is_string($this->key))
			$this->key = md5(serialize($this->key));

		if (empty($this->key))
			throw new Exception('Invalid cache key.');

		return "{$this->prefix}.{$this->key}";
	}

	public function store($val, $key = null) {
		if ($this->caching)
			return apc_store($this->key($key), $val);
	}

	public function fetch($key) {
		if ($this->caching)
			return apc_fetch($this->key($key));
	}

	public function delete($key) {
		if ($this->caching)
			return apc_delete($this->key($key));
	}
}

?>