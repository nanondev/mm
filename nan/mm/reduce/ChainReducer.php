<?php
namespace nan\mm\reduce;
use nan\mm;
use nan\mm\measure;
use nan\mm\abc;

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