<?php
namespace nan\mm\reduce;
use nan\mm;

class MeasureContext {
	var $time;
	function __construct($time=null) {
		if ($time==null) {
			$this->time=mm\time::nw(4,4,mm\note::nw("C"));
		} else {
			$this->time=$time;
		}
	}
	function withTime($time) {
		return new MeasureContext($time);
	}
	function time() {
		return $this->time;
	}
}

?>