<?php
namespace nan\mm;

class time extends MusicNode {
	var $quantity;
	var $duration;

	function __construct($quantity,$duration,$nodes=[]) {
		parent::__construct("time",$nodes);
		$this->quantity=$quantity;
		$this->duration=$duration;
	}
	
	static function nw($quantity,$duration,$nodes=[]) {
		return new time($quantity,$duration,$nodes);
	}

	function quantity() {
		return $this->quantity;
	}
	function duration() {
		return $this->duration;
	}

	function  toStringCompact() {
		return sprintf("(%s/%s)(%s)%s",$this->quantity,$this->duration,$this->toStringNodes(),$this->tag());
	}
}

?>