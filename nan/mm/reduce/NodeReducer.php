<?php
namespace nan\mm\reduce;
use nan\mm;
use nan\mm\measure;
use nan\mm\abc;

class NodeReducer {
	function reduce_nodes($nodes,$c) {
		$nodesOut=array();
		if (!is_array($nodes)) mm\warn("wrong argument: $nodes");
		foreach ($nodes as $ni) {
			$nodesOut[]=$this->reduce($ni,$c);
		}
		return $nodesOut;	
	}

	function reduce_pass($m,$c) {
		return $m->withNodes($this->reduce_nodes($m->nodes(),$c));
	}

	function createContext() {
		return new abc\AbcContext();
	}

	function reduce($m,$c=null) {
		if ($c==null) {
			$c=$this->createContext();
		}
		$name=$m->name();
		$fn="reduce_".$name;
		if (!method_exists($this,$fn)) {
		$fn="reduce_pass";
		}
		$mo=$this->$fn($m,$c);
		
		return $mo;
	}
}

?>
