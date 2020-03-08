<?php

namespace nan\mm;

class Up8thMul extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("Up8thMul",$nodes);		
	}

	static function nw($nodes=[]) {
		return new Up8thMul($nodes);
	}

	function  toStringCompact() {
		return sprintf("8thMul+%s",$this->toStringNodes());
	}
}

?>