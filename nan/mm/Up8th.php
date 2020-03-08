<?php

namespace nan\mm;

class up8th extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("up8th",$nodes);		
	}

	static function nw($nodes=[]) {
		return new up8th($nodes);
	}

	function  toStringCompact() {
		return sprintf("8th+%s",$this->toStringNodes());
	}
}

?>