<?php

class Page extends View {
	protected $withNavbar;
	public function __construct($name, array $data = array()) {
		$options = array_filter(explode(':', $name));

		parent::__construct(array_shift($options), $data);
		
		foreach ($options as $opt)
			$this->{$opt} = 1;
	}

	public function render($print = false) {
		$layout = new View('layout', $this->data);
		$layout->navbar = $this->withNavbar ? new View('navbar', $this->data) : '';
		$layout->content = new View($this->name, $this->data);
		return $layout->render($print);
	}
}

?>