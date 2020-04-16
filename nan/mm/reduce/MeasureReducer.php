<?php
namespace nan\mm\reduce;
use nan\mm;
namespace nan\mm\reduce;
use nan\mm;


class MeasureReducer extends NodeReducer {
	function reduceThenMeasure($m,$c) {
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

	function listToThen($ms) {		
		$first=null;
		for($i=count($ms)-1;$i>=0;$i--) {
			$mi=$m[$i];
			if ($first==null) {
				$first=$mi;
			} else  {
				$first=Then::nw($first,$mi);
			}
		}
		return $first;
	}

	function reduceThen($m,$c) {
		//return $this->reduce_then_measure($m,$c);		
		$measures=[];
		do {
			$m=$this->nextMeasure($m,$c);
			if ($m!=null) {
				$measures[]=$m;
			}
		} while($m!=null);
		return $this->listToThen($measures);
	}

	function reduceMeasure($m,$c) { 
		return $m; 
	}

	function reduceTime($m,$c) { 		
		$co=$c->withTime($m);
		return $this->reducePass($m,$co); 
	}

	function createContext() {
		return new MeasureContext();
	}

}

?>