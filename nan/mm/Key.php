<?php
namespace nan\mm;

class key extends MusicNode	 {
	var $reps;
	function __construct($key,$nodes=[]) {
		parent::__construct("key",$nodes);
		$this->key=$key;
	}

	static function nw($key,$nodes=[]) {
		return new key($key,$nodes);
	}

	function key() {
		return $this->key;
	}
	function  toStringCompact() {
		return sprintf("%s:%s",$this->key,$this->toStringNodes());
	}

	function toStringSeparator() {
		return "";
	}
}

?>