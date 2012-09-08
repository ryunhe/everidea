<?php

use EDAM\UserStore\UserStoreClient, EDAM\NoteStore\NoteStoreClient;

class Evernote {
	protected static $_userStore;
	protected static $_noteStore;
	protected $_data = array();
	protected $_type = null;

	public function __construct($guid = null) {
		$class = "EDAM\\Types\\{$this->_type}";
		$this->{$this->_type} = new $class;
		if (!is_null($guid))
			$this->load($guid);
	}

	public function __get($field) {
		return property_exists($this->{$this->_type}, $field) ? $this->{$this->_type}->{$field} :
			(isset($this->_data[$field]) ? $this->_data[$field] : null);
	}

	public function __set($field, $value) {
		property_exists($this->{$this->_type}, $field) ? $this->{$this->_type}->{$field} = $value : $this->_data[$field] = $value;
	}

	public static function _userStore() {
		if (self::$_userStore)
			return self::$_userStore;

		$userStoreHttpClient = new THttpClient(NOTESTORE_HOST, NOTESTORE_PORT, "/edam/user", NOTESTORE_PROTOCOL);
		$userStoreProtocol = new TBinaryProtocol($userStoreHttpClient);
		return self::$_userStore = new UserStoreClient($userStoreProtocol, $userStoreProtocol);
	}

	public static function _noteStore() {
		if (self::$_noteStore)
			return self::$_noteStore;

		$part = parse_url(self::_userStore()->getNoteStoreUrl(AUTH_TOKEN));

		$noteStoreHttpClient = new THttpClient(NOTESTORE_HOST, NOTESTORE_PORT, $part['path'], NOTESTORE_PROTOCOL);
		$noteStoreProtocol = new TBinaryProtocol($noteStoreHttpClient);
		return self::$_noteStore = new NoteStoreClient($noteStoreProtocol, $noteStoreProtocol);
	}
}

?>