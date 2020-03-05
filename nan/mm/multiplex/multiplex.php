<?php
namespace nan\mm\multiplex;
use nan\mm;
use nan\mm\reduce;

class multiplex extends mm\MusicNode {
	var $channels;
	function __construct($channels,$nodes=[]) {
		parent::__construct("multiplex",$nodes);
		$this->channels=$channels;
	}

	function channels() {
		return $this->channels;
	}
}

?>