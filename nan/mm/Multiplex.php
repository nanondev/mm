<?php
namespace nan\mm;

class multiplex extends MusicNode {
	var $channels;
	function __construct($channels,$nodes=[]) {
		parent::__construct("multiplex",$nodes);
		$this->channels=$channels;
	}

	static function nw($channels,$nodes=[]) {
		return new multiplex($channels,$nodes);
	}
	
	function channels() {
		return $this->channels;
	}
}

?>