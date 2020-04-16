<?php
namespace nan\mm;

class Measure extends UnaryNode {

	function __construct($uniqueNode) {
		parent::__construct($uniqueNode);
	}

	static function nw($uniqueNode) {
		return new Measure($uniqueNode);
	}

	static function clazz() {
		return get_class(Measure::nw(Note::nw("C")));
	}

	function toStringCompact() {
		return "|".($this->toStringNodes()).$this->tag();
	}
}

?>