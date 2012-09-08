<?php

class Notebook extends Evernote {
	protected $_type = 'notebook';
	protected $notebook;

	public function load($guid) {
		if (!empty($guid)) {
			$cache = new Cache('notebook');
			if (!($notebook = $cache->fetch($guid))) {
				$notebook = self::_noteStore()->getNotebook(AUTH_TOKEN, $guid);
				$cache->store($notebook);
			}
			$this->notebook = $notebook;
		}
		
		return $this;
	}

	public static function all() {
		return self::_noteStore()->listNotebooks(AUTH_TOKEN);
	}
}

?>