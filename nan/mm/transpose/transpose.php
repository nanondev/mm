<?php
namespace nan\mm\transpose;
use nan\mm;
use nan\mm\reduce;

class TransposeContext {
	var $transposeDistance;
	function __construct($transposeDistance=0) {
		$this->transposeDistance=$transposeDistance;
	}

	function withTransposeDistance($transposeDistance) {
		return new TransposeContext($transposeDistance);
	}
	function transposeDistance() {
		return $this->transposeDistance;
	}
	function __toString() {
		return sprintf("AbcContext <tr:%s>",$this->transposeDistance);
	}
}

class TransposeReducer extends reduce\NodeReducer {
	function reduce_transpose($m,$c,$transposeDistance) {	
		$co=$c->withTransposeDistance($c->transposeDistance()+$transposeDistance);
		if (count($m->nodes())>0) {
			$mo=new mm\then($this->reduce_nodes($m->nodes(),$co));
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