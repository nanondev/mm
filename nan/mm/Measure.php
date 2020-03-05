<?php
namespace nan\mm;

class measure extends MusicNode {

	function __construct($nodes=[]) {
		parent::__construct("measure",$nodes);
	}

	static function nw($nodes=[]) {
		return new measure($nodes);
	}

	function toStringCompact() {
		return "|".($this->toStringNodes()).$this->tag();
	}
}

?>