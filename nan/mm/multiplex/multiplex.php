<?php
namespace nan\mm\multiplex;
use nan\mm;
use nan\mm\reduce;

class multiplex extends mm\MusicNode {
	var $channels;
	function __construct($channels,$nodes=[]) {
		parent::__construct($nodes);
		$this->channels=$channels;
	}

	function channels() {
		$this->channels;
	}
}

class MultiplexReducer extends reduce\NodeReducer {
	function reduce_multiplex($m,$c) {
		$reducedNodes=[];
		foreach($m->nodes() as $mi) {
			$merged=[];
			for($i=0;$i<$m->channels();$i++) {
				$merged[]=$mi; //repetimos tantas veces como canales haya
			}
			$miReduced=new mm\merge($merged);
			$reducedNodes[]=$miReduced;
		}
		return count($reduceNodes)==1 ? $reducedNodes[0] : mm\then($reducedNodes);
	}
}

?>