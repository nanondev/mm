<?php
namespace nan\mm;

class Tempo extends UnaryNode {
	var $beatNote,$beatsByMinute;
	function __construct($beatNote,$beatsByMinute,$uniqueNode) {
		parent::__construct($uniqueNode);
		$this->beatNote=$beatNote;
		$this->beatsByMinute=$beatsByMinute;
	}

	static function nw($beatNote,$beatsByMinute,$uniqueNode) {
		return new Tempo($beatNote,$beatsByMinute,$uniqueNode);
	}

	function beatNote() {
		return $this->beatNote;
	}
	function beatsByMinute() {
		return $this->beatsByMinute;
	}

	static function clazz() {
		return get_class(Tempo::nw(1,60,Note::nw("C")));
	}

	function toStringAttributes() {
		return sprintf("beatNote:%s,beatsByMinute:%s",$this->beatNote,$this->beatsByMinute);
	}
}

?>