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
	function distribute_node($m,$c,$clazz) {
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
	}

	function reduce($m,$c=null) {
		//reduce n1[n2:ns]==>n1 [reduce n1: reduce_nodes ns]
		//reduce n1[(up8th ns2):ns3]==>n1 [reduce n1: reduce_nodes ns]
		
		//return $this->distribute_node($m,$c,"\\nan\\mm\\up8th");
		return $this->reduce_up8th($m,$c);
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