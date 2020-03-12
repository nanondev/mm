<?php
namespace nan\mm\reduce;
use nan\mm;


/*
Casos para up8th: 

- up8th note : transponer nota.
- up8th parallel : parallel up8th+
- up8th then : then up8th+
- up8th  
*/

class NodeList extends mm\MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("NodeList",$nodes);
	}

	static function nw($nodes=[]) {
		return new NodeList($nodes);
	}
}

class Up8thReducer extends NodeReducer {
	/*function distribute_node($m,$c,$clazz) {
		$distributed=[];
		foreach($m->nodes() as $ni) {
			if (get_class($ni)==$clazz) {
				$distributed[]=$m->withNodes($ni);	
			} else {
				$ni;
			}
			
		}
		return $distributed;
	}

	function reduce_up8th_note($m,$c) {
		$note=$m->uniqueNode();
		$noteTransposed=$note->withTransposeDistance(12);

		return $m->withNodes([$noteTransposed]);
	}

	function reduce_up8th($m,$c) {
		print "reduce-8:".$m->toStringTree()."\n";
		if ($m->hasUniqueNodeOfType("\\nan\\mm\\note")) {
			return $this->reduce_up8th_note($m,$c);
		} else {
			print "reduciendo-nodos\n";
			$mo=NodeList::nw($this->distribute_node($m,$c,"\\nan\\mm\up8th"));
			print "mo:".$mo->toStringTree()."\n";
			return $mo;
		}
	}*/

	function matchIgnore($m,$c) {
		return get_class($m)==\nan\mm\up8th && $m->hasSingleNode() && !$m->hasSingleNodeOfType(\nan\mm\note);
	}

	function matchConsume($m,$c) {
		return get_class($m)==\nan\mm\up8th && $m->hasSingleNode() && $m->hasSingleNodeOfType(\nan\mm\note);
	}

	function matchDistribute($m,$c) {
		return get_class($m)==\nan\mm\up8th && !$m->hasSingleNode();
	}

	function matchExplore($m,$c) {
		return get_class($m)!=\nan\mm\up8th && !$m->hasSingleNode();
	}

	function reduceIgnore($m,$c) {
		mm\warn("ignoring reduce up8th at $m");
		return $m;
	}

	function reduceConsume($m,$c) {
		return $m->withTransposeDistance($m->transposeDistance()+12);
	}

	function reduceDistribute($m,$c) {
		
	}

	function reduceExplore($m,$c) {
		return $m->withNodes($this->reduceNodes($m->nodes(),$c));
	}
	
	function reduceNodes($m,$c) {
		$ns=[];
	}

	function reduce($m,$c=null) {
		$mo=null;
		if ($this->matchIgnore($m,$c)) $mo=$this->reduceIgnore($m,$c);
		else if ($this->matchConsume($m,$c)) $mo=$this->reduceConsume($m,$c);
		else if ($this->matchDistribute($m,$c)) $mo=$this->reduceDistribute($m,$c);
		else if ($this->matchExplore($m,$c)) $mo=$this->reduceExplore($m,$c);
		return $mo;
	}
}

class TransposeReducer extends NodeReducer {
	function reduce_transpose($m,$c,$transposeDistance) {	
		$co=$c->withTransposeDistance($c->transposeDistance()+$transposeDistance);
		if (count($m->nodes())>0) {
			$mo=mm\then::nw($this->reduce_nodes($m->nodes(),$co));
		} else {
			$mo=$m->uniqueNode();
		}
		return $mo->withNodes($this->reduce_nodes($mo->nodes(),$co));
	}

	function reduce_up8th($m,$c) {
		return $this->reduce_transpose($m,$c,12);
	}

	function reduce_down8th($m,$c) {
		return $this->reduce_transpose($m,$c,-12);
	}

	function reduce_note($m,$c) {	
		if ($c->transposeDistance()!=0) {
			$newTag=$m->tag()->withTransposeDistance($c->transposeDistance());
			$rr= 
				$m->withTag($newTag)->withNodes($this->reduce_nodes($m->nodes(),$c));
				 // pasamos la etiqueta de transposición de contexto al nodo.		
			return $rr;
		} else {
			return $this->reduce_pass($m,$c);
		}
	}

	function createContext() {
		return new TransposeContext();
	}
}

?>