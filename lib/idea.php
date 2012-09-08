<?php

class Idea extends Evernote {
	protected $_type = 'note';
	protected $_tags = array();
	protected $note;

	public $tags;
	public $likers;

	public function __construct($guid = null) {
		$this->tags = new Types\Set();
		$this->likers = new Types\Set();

		parent::__construct($guid);
	}

	public function load($guid) {
		if ($guid instanceof EDAM\NoteStore\NoteMetadata) {
			$this->note = $guid;
			$this->_init();
		} elseif (!empty($guid)) {
			$cache = new Cache('note');
			if (!($note = $cache->fetch($guid))) {
				$note = self::_noteStore()->getNote(AUTH_TOKEN, $guid, true, true, true, true);
				$cache->store($note);
			}
			$this->note = $note;
			$this->_init();
		}

		return $this;
	}

	public function save() {
		$this->_calc();

		$action = $this->note->guid ? 'updateNote' : 'createNote';
		$note = self::_noteStore()->{$action}(AUTH_TOKEN, $this->note);
		$this->note = self::_noteStore()->getNote(AUTH_TOKEN, $note->guid, true, true, true, true);

		$cache = new Cache('note');
		$cache->store($this->note, $this->note->guid);

		return $this;
	}

	public function delete($guid = null) {
		$cache = new Cache('note');
		$cache->delete($this->note->guid);

		$this->note = new Note();

		return self::_noteStore()->deleteNote(AUTH_TOKEN, $guid ?: $this->guid);
	}

	public function archive() {
		$this->archived = true;
		$this->tags->add('[archived]');
		return $this;
	}

	public function attris() {
		$attris = array();
		foreach ($this as $field => $value) {
			$property = new ReflectionProperty($this, $field);
			if ($property->isPublic())
				$attris[$field] = ($value instanceof Types\Set ? $value->members() : $value);
		}
		return $this->_data + $attris;
	}

	public static function search($words = null, $start = 0, $count = 10, $sort = SortOrder::CREATED) {
		$filter = new EDAM\NoteStore\NoteFilter();
		$filter->words = $words;
		$filter->order = $sort;
		$filter->version = time();
		$filter->notebookGuid = EVERIDEA_NOTEBOOK;
		return new IdeaList($filter, $start, $count);
	}

	protected function _init() {
		# init _data
		$data = array();
		if (preg_match("#<pre>(.*)</pre>#ms", $this->note->content, $matches)) {
			if (preg_match_all("#(\w+):(.*)\n#", rtrim($matches[1]) . "\n", $matches)) {
				foreach ($matches[1] as $key => $field) {
					if ($value = $matches[2][$key]) {
						if ($this->{$field} instanceof Types\Set)
							$this->{$field}->add(explode(',', $value));
						else
							$data[$field] = $value;
					}
				}
			}
		}
		$this->_data = str_replace(array('\\\\', '\n'), array("\\", "\n"), $data);

		# init _tags
		if (!empty($this->note->tagGuids)) {
			$cache = new Cache('note_tag');
			foreach ($this->note->tagGuids as $guid) {
				if (!($tag = $cache->fetch($guid))) {
					$tag = self::_noteStore()->getTag(AUTH_TOKEN, $guid);
					$cache->store($tag);
				}
				$this->tags->add($tag->name);
				$this->_tags[$tag->guid] = $tag->name;
			}
		}

		return $this;
	}

	protected function _calc() {
		# content
		$data = str_replace(array('\\', "\n"), array('\\', '\n'), $this->_data);
		$content = "likers:{$this->likers}\n";
		foreach ($data as $key => $val) {
			$content .= trim("{$key}:{$val}") . "\n";
		}
		$this->note->content = <<<STR
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">
<en-note>
<pre>{$content}</pre>
</en-note>
STR;

		# tags
		$this->note->tagNames = $this->tags->members();
		$this->note->tagGuids = array_keys(array_intersect($this->_tags, $this->tags->members()));

		# title
		$this->note->title = $this->likers->count() . '|' . mb_ereg_replace('\s+', ' ', $this->summary);

		# notebook
		$this->note->notebookGuid = EVERIDEA_NOTEBOOK;

		return $this;
	}
}

?>