<?php
namespace nan\mm;

class Then extends BinaryNode {

	function __construct($firstNode,$secondNode) {
		parent::__construct($firstNode,$secondNode);
	}


	static function nw($firstNode=null,$secondNode=null) {
		if ($firstNode==null) $firstNode=Note::nw("C");
		if ($secondNode==null) $secondNode=Note::nw("C");
		return new then($firstNode,$secondNode);
	}

	static function clazz() {
		return get_class(then::nw(note::nw("A"),note::nw("B")));
	}

	function toStringCompact() {
		return "".($this->toStringNodes());
	}

	function toStringSeparator() {
		return "";
	}
}

?>