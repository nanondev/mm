<?php
namespace nan\mm\reduce;
use nan\mm;
use nan\mm\abc;


class NodeReducerContext {} // extends abc\AbcContext {}

class NodeReducer {
	function reducePassUnary($m,$c) {
		return $m
			->withUniqueNode($this->reduce($m->uniqueNode(),$c));
	}

	function reducePassBinary($m,$c) {
		return $m
			->withFirstNode($this->reduce($m->firstNode(),$c))
			->withSecondNode($this->reduce($m->secondNode(),$c));
	}

	function reducePass($m,$c) {
		if ($m instanceof mm\TerminalNode) return $m;		
		if ($m instanceof mm\UnaryNode) return $this->reducePassUnary($m,$c);
		if ($m instanceof mm\BinaryNode) return $this->reducePassBinary($m,$c);	
		mm\err("unsupported node type: $m class:".get_class($m));
	}

	function createContext() {
		return new NodeReducerContext();
	}

	function reduce($m,$c=null) {
		if ($m==null) mm\err("reduce call on null");
		if ($c==null) {
			$c=$this->createContext();
		}

		$frags=explode("\\",$m->clazz());
		$classFrag=ucfirst($frags[count($frags)-1]);
		$fn="reduce".$classFrag;

		if (!method_exists($this,$fn)) {
			$fn="reducePass";
		}
		$mo=$this->$fn($m,$c);
		return $mo;
	}
}

?>
