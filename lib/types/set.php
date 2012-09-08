<?php

namespace Types;

class Set implements \IteratorAggregate {
	protected $_members = array();

	public function __construct(Array $members = array()) {
		$this->add($members);
	}

	public function getIterator() {
		return new \ArrayIterator($this->members());
	}

	public function add($member) {
		if (is_array($member))
			foreach ($member as $mem)
				$this->add($mem);
		else
			$this->_members[$member] = 1;
		return $this;
	}

	public function remove($member) {
		unset($this->_members[$member]);
	}

	public function exists($member) {
		return isset($this->_members[$member]);
	}

	public function count() {
		return count($this->_members);
	}

	public function members() {
		return array_keys($this->_members);
	}

	public function __toString() {
		return implode(',', $this->members());
	}
}

?>