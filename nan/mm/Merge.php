<?php

namespace nan\mm;

class merge extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("merge",$nodes);
	}

	static function nw($nodes=[]) {
		return new merge($nodes);
	}

	function toStringCompact() {
		return "".($this->toStringNodes()); //dejamos simplemente los corchetes.
	}

	function toStringSeparator() {
		return "";
	}
}

?>