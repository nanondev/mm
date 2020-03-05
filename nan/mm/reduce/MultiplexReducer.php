<?php
namespace nan\mm\reduce;
use nan\mm;



class MultiplexReducer extends NodeReducer {
	function reduce_multiplex($m,$c) {
		$reducedNodes=[];
		foreach($m->nodes() as $mi) {
			$merged=[];
			for($i=0;$i<$m->channels();$i++) {
				$merged[]=$mi; //repetimos tantas veces como canales haya
			}
			$miReduced=mm\merge::nw($merged);
			$reducedNodes[]=$miReduced;
		}

		return count($reducedNodes)==1 ? $reducedNodes[0] : mm\then::nw($reducedNodes);
	}
}

?>