<?php

class User extends Evernote {
	protected $_type = 'user';
	protected $user;

	public function load($token) {
		if (!empty($token)) {
			$cache = new Cache('user');
			if (!($user = $cache->fetch($token))) {
				$user = self::_userStore()->getUser($token);
				$cache->store($user);
			}
			$this->user = $user;
		}
		
		return $this;
	}
}

?>