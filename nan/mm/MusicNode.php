<?php

namespace nan\mm;

/* 
 * base para nodos del arbol musical.
 *
 * propiedades:
 * -es inmutable
 * 
 */
class MusicNode {
	var $name;
	var $tag;
	var $nodes=array();

	function __construct($name,$nodes=array(),$tag=null) {
		$this->name=$name;
		if (!is_array($nodes)) $nodes=array($nodes);
		if ($tag==null) $tag=new MusicNodeTag();
		$this->nodes=$nodes;
		$this->tag=$tag;
	}	

	function name() {
		return $this->name;
	}
	function nodes() {
		return $this->nodes;
	}

	function withNodes($nodes) {
		$mm=clone $this;
		$mm->nodes=$nodes;
		return $mm;
	}	

	function addNode($node) {
		$mm=clone $this;
		$mm->nodes=$this->nodes;
		$mm->nodes[]=$node;
		return $mm;
	}

	function wrap($m) {
		$wrapped=$m->withNodes([$this]);
		return $wrapped;
	}

	function withTag($tag) {
		//throw new exception("class must implement withTag: ".get_class($this));
		$mm=clone $this;
		$mm->tag=$tag;
		return $mm;
	}

	function tag() {
		return $this->tag;
	}

	function hasNodes() {
		return count($this->nodes)>0;
	}
	function uniqueNode() {
		if (count($this->nodes())>1) {
			err("unique node expected, but many found: $this");
		} else if (count($this->nodes())==0) {
			err("unique node expected, but none found: $this");
		}
		return $this->nodes[0];
	}

	function firstNode() {
		return $this->nodes[0];		
	}

	function __toString() {
		return $this->toStringCompact();
	}
	
	function toStringTree() {
		$str=sprintf("%s:%s%s%s ",$this->name(),$this->toStringComplementary(),$this->tag->__toString(),$this->toStringNodes(true));
		return $str;
	}

	function toStringCompact() {
		$name=$this->name;
		$compl=$this->toStringComplementary();
		$separator=$this->toStringSeparator();
		$nodesStr=$this->toStringNodes();
		return sprintf('%s%s%s%s%s',$name,$separator,$compl,$this->tag->__toString(),$nodesStr);
	}


	function toStringNodes($asTree=false) {
		$nodesStr="";
		$separator=$this->toStringSeparator();
		foreach($this->nodes() as $ni) {
			$nodesStr.=sprintf("%s%s",$asTree ? $ni->toStringTree() : $ni->__toString(),$separator);
		}			
		if (count($this->nodes())>1) {
			$nodesStr="[$nodesStr]";
		}		
		return $nodesStr;
	}

	function toStringSeparator() {
		return " ";
	}	

	function toStringComplementary() {
		return "";
	}
}

?>