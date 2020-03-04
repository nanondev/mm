<?php
namespace nan\mm\reduce;
use nan\mm;
use nan\mm\measure;
use nan\mm\abc;

class ReduceNs {}

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

class ChainReducer extends NodeReducer {
	var $reducers=[];
	
	function __construct($reducers=[]) {
		$this->reducers=$reducers;
	}

	function withReducer($reducer) {
		$newReducers=$this->reducers;
		$newReducers[]=$reducer;
		return new ChainReducer($newReducers);
	}

	function reduce($m,$c=null) {
		mm\debug("ChainReducer: reduce: init");
		$mi=$m;
		foreach($this->reducers as $ri) {
			$ci=$c;
			if ($c==null) {
				$ci=$ri->createContext();
			}
			mm\debug("ChainReducer: reduce: ".get_class($ri));
			$mi=$ri->reduce($mi,$c);
		}
		return $mi;
	}
}

?>