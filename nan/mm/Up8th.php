<?php

namespace nan\mm;

class Up8th extends UnaryNode {
	function __construct($uniqueNode) {
		parent::__construct($uniqueNode);		
	}

	static function nw($uniqueNode) {
		return new Up8th($uniqueNode);
	}

	static function clazz() {
		return get_class(Up8th::nw(note::nw("C")));
	}
}

?>