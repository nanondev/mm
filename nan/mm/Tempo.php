<?php
namespace nan\mm;

class tempo extends MusicNode {
	var $beatNote,$beatsByMinute;
	function __construct($beatNote,$beatsByMinute,$nodes=[]) {
		parent::__construct("tempo",$nodes);
		$this->beatNote=$beatNote;
		$this->beatsByMinute=$beatsByMinute;
	}

	static function nw($beatNote,$beatsByMinute,$nodes=[]) {
		return new tempo($beatNote,$beatsByMinute,$nodes);
	}

	function beatNote() {
		return $this->beatNote;
	}
	function beatsByMinute() {
		return $this->beatsByMinute;
	}
}

?>