<?php

namespace nan\mm;

class arp extends MusicNode {
	var $orderPattern;
	var $lengthInNotes;

	function __construct($orderPattern,$lengthInNotes,$nodes=[]) {
		parent::__construct("arp",$nodes);
		$this->orderPattern=$orderPattern;
		$this->lengthInNotes=$lengthInNotes;
	}	

	function nw($orderPattern,$lengthInNotes,$nodes=[]) {
		return new arp($orderPattern,$lengthInNotes,$nodes);
	}

	function chord() {
		$chord=$this->uniqueNode();
		return $chord;
	}

	function orderPattern() {
		return $this->orderPattern;
	}

	function lengthInNotes() {
		return $this->lengthInNotes;
	}

}

?>
