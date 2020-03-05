<?php
namespace nan\mm;

class then extends MusicNode {

	function __construct($nodes=[]) {
		parent::__construct("then",$nodes);
	}

	static function nw($nodes=[]) {
		return new then($nodes);
	}

	function toStringCompact() {
		return "".($this->toStringNodes());
	}
	function toStringSeparator() {
		return "";
	}
}

?>