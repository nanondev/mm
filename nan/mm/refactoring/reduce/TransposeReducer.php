<?php
namespace nan\mm\reduce;
use nan\mm;

class TransposeReducer extends NodeReducer {

}
class Up8thReducer extends NodeReducer {

	function matchConsume($m,$c) {
		return get_class($m)==mm\up8th::clazz() && $m->uniqueNodeHasClazz(mm\note::clazz());
	}

	function matchThenDistribute($m,$c) {
		return get_class($m)==mm\up8th::clazz() && $m->uniqueNodeHasClazz(mm\then::clazz());
	}

	function matchParallelDistribute($m,$c) {
		return get_class($m)==mm\up8th::clazz() && $m->uniqueNodeHasClazz(mm\parallel::clazz());
	}

	function reduceConsume($m,$c) {
		$note=$m->uniqueNode();
		return $note->withTransposeDistance($note->transposeDistance()+12);
	}
	
	function reduceThenDistribute($m,$c) {
		$then=$m->uniqueNode();
		return mm\then::nw(
			$this->reduce(mm\up8th::nw($then->firstNode()),$c),
			$this->reduce(mm\up8th::nw($then->secondNode()),$c)
		);
	}

	function reduceParallelDistribute($m,$c) {
		$then=$m->uniqueNode();
		return mm\then::nw(
			$this->reduce(mm\up8th::nw($then->firstNode()),$c),
			$this->reduce(mm\up8th::nw($then->secondNode()),$c)
		);
	}
	
	function reduce($m,$c=null) {
		$mo=$m;
		if ($this->matchConsume($m,$c)) $mo=$this->reduceConsume($m,$c);
	
		else if ($this->matchThenDistribute($m,$c)) $mo=$this->reduceThenDistribute($m,$c);
	
		else if ($this->matchParallelDistribute($m,$c)) $mo=$this->reduceParallelDistribute($m,$c);
	
		return $mo;
	}
}


?>