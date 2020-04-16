<?php

namespace nan\mm;

class Arp extends TerminalNode {
	var $orderPattern;
	var $lengthInNotes;

	function __construct($orderPattern,$lengthInNotes,$chord) {
		parent::__construct();
		$this->orderPattern=$orderPattern;
		$this->lengthInNotes=$lengthInNotes;
		$this->chord=$chord;
	}	

	static function nw($orderPattern,$lengthInNotes,$notes) {
		return new Arp($orderPattern,$lengthInNotes,$notes);
	}

	function chord() {
		return $this->chord;
	}

	function orderPattern() {
		return $this->orderPattern;
	}

	function toStringAttributes() {
		$orderPatternStr=$this->toStringList($this->orderPattern);
		$chordStr=$this->chord()->toStringTree();
		$lengthInNotes=$this->lengthInNotes;
		return "orderPattern:$orderPatternStr,lengthInNotes:$lengthInNotes,chord:$chordStr";
	}

	function lengthInNotes() {
		return $this->lengthInNotes;
	}

	static function clazz() {
		return get_class(Arp::nw([1],1,Chord::nw("C")));
	}
}

?>
