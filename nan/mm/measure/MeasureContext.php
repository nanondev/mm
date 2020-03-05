<?php
namespace nan\mm\measure;
use nan\mm;
use nan\mm\measure;
class MeasureContext {
	var $time;
	function __construct($time=null) {
		if ($time==null) {
			$this->time=mm\time::nw(4,4);
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