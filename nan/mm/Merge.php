<?php

namespace nan\mm;

class Merge extends BinaryNode {
	function __construct($firstNode,$secondNode) {
		parent::__construct($firstNode,$secondNode);
	}

	static function nw($firstNode,$secondNode) {
		return new Merge($firstNode,$secondNode);
	}

	static function clazz() {
		return get_class(Parallel::nw(note::nw("A"),note::nw("B")));
	}
}

?>