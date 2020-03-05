<?php
namespace nan\mm;

class header extends MusicNode {
	var $header;
	function __construct($header,$nodes=[]) {
		parent::__construct("header",$nodes);
		$this->header=$header;
	}

	static function nw($header,$nodes=[]) {
		return new header($header,$nodes);
	}

	function header() {
		return $this->header;
	}

	function toStringCompact() {
		return sprintf("<%s>:\n%s\n",join(', ',$this->header),($this->toStringNodes()));
	}
}

?>