<?php
namespace nan\mm\abc;
use nan\mm;
use nan\mm\reduce;

class StringContext  {}

class StringReducer {
	function reduce_pass_unary($m,$c) {
		return $m
			->withUniqueNode($this->reduce($m->uniqueNode(),$c));
	}

	function reduce_pass_binary($m,$c) {
		return $m
			->withFirstNode($this->reduce($m->firstNode(),$c))
			->withSecondNode($this->reduce($m->secondNode(),$c));
	}

	function reduce_pass($m,$c) {
		if ($m instanceof mm\TerminalNode) return "";		
		if ($m instanceof mm\UnaryNode) return $this->reduce_pass_unary($m,$c);
		if ($m instanceof mm\BinaryNode) return $this->reduce_pass_binary($m,$c);	
		mm\err("unsupported node type: $m class:".get_class($m));
	}

	function reduce($m,$c=null) {
		if ($c==null) {
			$c=$this->createContext();
		}

		$frags=explode("\\",$m->clazz());
		$fn="reduce".ucfirst($frags[count($frags)-1]);
		if (!method_exists($this,$fn)) {
			$fn="reducePass";
		}

		//mm\err("string-reduce:$fn");

		$s=$this->$fn($m,$c);
		return $s;
	}

	function createContext() {
		return new StringContext();
	}

}

?>
