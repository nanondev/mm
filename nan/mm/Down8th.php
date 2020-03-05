<?php

namespace nan\mm;

class down8th extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("down8th",$nodes);		
	}

	static function nw($nodes=[]) {
		return new down8th($nodes);
	}

	function  toStringCompact() {
		return sprintf("8th-%s",$this->toStringNodes());
	}
}

?>
