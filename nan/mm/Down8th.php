<?php

namespace nan\mm;

class Down8th extends UnaryNode {
	function __construct($uniqueNode) {
		parent::__construct($uniqueNode);		
	}

	static function nw($uniqueNode) {
		return new Down8th($uniqueNode);
	}

	static function clazz() {
		return get_class(Down8th::nw(note::nw("C")));
	}
}

?>
