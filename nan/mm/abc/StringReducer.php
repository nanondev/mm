<?php
namespace nan\mm\abc;
use nan\mm;
use nan\mm\reduce;

class StringContext  {}

class StringReducer {
	function reduce_nodes($nodes,$c) {
		$s="";
		if (!is_array($nodes)) warn("wrong argument: $nodes");
		foreach ($nodes as $mi) {
			$s.=$this->reduce($mi);
		}
		return $s;	
	}

	function reduce($m,$c=null) {
		if ($c==null) {
			$c=$this->createContext();
		}
		$fn="reduce_".($m->name());
		if (!method_exists($this,$fn)) {
			$fn="reduce_pass";
		}
		$s=$this->$fn($m,$c);
		return $s;
	}

	function createContext() {
		return new StringContext();
	}

}

?>
