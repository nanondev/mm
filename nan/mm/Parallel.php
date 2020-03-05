<?php

namespace nan\mm;

class parallel extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("parallel",$nodes);
	}

		static function nw($nodes=[]) {
		return new parallel($nodes);
	}

}

?>