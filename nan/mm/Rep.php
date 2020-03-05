<?php

namespace nan\mm;

class rep extends MusicNode {
	var $reps;
	function __construct($reps,$nodes=[]) {
		parent::__construct("rep",$nodes);
		$this->reps=$reps;
	}

	static function nw($reps,$nodes=[]) {
		return new rep($reps,$nodes);
	}

	function reps() {
		return $this->reps;
	}
	function  toStringCompact() {
		return sprintf("%s*%s",$this->reps,$this->toStringNodes());
	}

	function toStringSeparator() {
		return "";
	}
}


?>