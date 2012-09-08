<?php

class IdeaList implements IteratorAggregate {
	protected $_metaList;
	public $total = 0;

	public function __construct($filter, $start, $count) {
		$cache = new Cache('note_list', false);
		if (!($metaList = $cache->fetch(compact('filter', 'start', 'count')))) {
			$metaList = Evernote::_noteStore()->findNotesMetadata(AUTH_TOKEN, $filter, $start, $count,
				new EDAM\NoteStore\NotesMetadataResultSpec());
			$cache->store($metaList);
		}

		$this->_metaList = $metaList;
		$this->total = $metaList->totalNotes;
	}

	public function __get($field) {
		return $this->_metaList->{$field};
	}

	public function total() {
		return $this->_metaList->totalNotes;
	}

	public function getIterator() {
		return new ArrayIterator(array_map(function($note) {
			return new Idea($note->guid);
		}, $this->_metaList->notes));
	}
}

?>