<?php
namespace nan\mm\reduce;
use nan\mm;
namespace nan\mm\reduce;
use nan\mm;


class MeasureReducer extends NodeReducer {
	function reduce_then_simplify($m,$c) {
		return $m->uniqueNode();
	}

	function reduce_then_measure($m,$c) {
		$nodes=$m->nodes();
		$partial_nodes=array();
		$measured_nodes=array();	
		$measure_size=$c->time()->quantity();
		$partial_durations=0;
		for($i=0;$i<count($nodes);$i++){
			$ni=$nodes[$i];
			$partial_nodes[]=$ni;
			$partial_durations+=$ni->duration();
			$is_measure_full=$partial_durations>=$measure_size;
			if ($is_measure_full) {
				$measured_nodes[]=new mm\measure($partial_nodes,$c);
				$partial_nodes=array();
				$partial_durations=0;
			}
		}
		if (count($partial_nodes)>0) { // agregamos el resto inconcluso
			mm\warn("partial measure found in: $m");
			$measured_nodes[]=new mm\measure($partial_nodes,$c);
		}
		$new_then=new mm\then($measured_nodes);
		//debug("new_then:$new_then");
		return $new_then;		
	}

	function reduce_then ($m,$c) {
		$simplify=$m->hasUniqueNodeOfType("nan\\mm\\then");
		if ($simplify) return $this->reduce_then_simplify($m,$c);
		return $this->reduce_then_measure($m,$c);
	}

	function reduce_measure($m,$c) { 
		return $m; 
	}

	function reduce_time($m,$c) { 
		$co=$c->withTime($m); return $this->reduce_pass($m,$co); 
	}

}

?>