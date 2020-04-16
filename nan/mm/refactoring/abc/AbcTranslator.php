<?php
namespace nan\mm\abc;
use nan\mm;
use nan\mm\reduce;

class AbcTranslator extends StringReducer {
	function reduce_tempo($m,$c) {
		$nodes=$m->nodes();	
		$n=$m->beatNote();
		$m=$m->beatsByMinute();
		$s="Q:$n=$m\n";
		$s.=$this->reduce_nodes($nodes,$c);
		return $s;
	}

	function reduce_time($m,$c) {
		$nodes=$m->nodes();	
		$q=$m->quantity();
		$d=$m->duration();
		$s="M:$q/$d\n";
		$s.=$this->reduce_nodes($nodes,$c);
		return $s;	
	}
	function reduce_key($m,$c) {
		$nodes=$m->nodes();	
		$k=$m->key();
		$s="K:$k\n";
		$s.=$this->reduce_nodes($nodes,$c);
		return $s;
	}

	function reduce_rep($m,$c) {
		$nodes=$m->nodes();	
		$s="";
		for($i=0;$i<$m->reps();$i++) {
			for ($j=0;$j<count($nodes);$j++) {
				$s.=$this->reduce($nodes[$j],$c);
			}
		}
		return $s;
	}

	function reduce_then($m,$c) {
		return $this->reduce_nodes($m->nodes(),$c);
	}

	function reduce_header($m,$c) {
		$nodes=$m->nodes();
		$header=$m->header();
		$composer=$header["composer"];	
		$title=$header["title"];
		$s="X:1\nT:$title\nC:$composer\n";
		return $s.$this->reduce_nodes($m->nodes(),$c);
	}

	function reduce_note($m,$c) {
		$t=$m->tag()->transposeDistance();
		$up=($t>0);
		$down=($t<0);
		$o=abs($t)/12;

		$octave_suffix="";
		if ($up) {
			while ($o-->0) {$octave_suffix.="'";};
		}
		if ($down) {
			while ($o-->0) {$octave_suffix.=",";};
		}
		return $m->accidental().$m->note().$m->toStringDuration().$octave_suffix;
	}

	function reduce_merge($m,$c) { // TODO URG mergear todos
		$nodes=$m->nodes();
		$s="";
		foreach($nodes as $ni) {
		 $s.=$this->reduce($ni,$c);
		}
		return $s;
	}

	function reduce_measure($m,$c) {
		//print "reduce_measure: ".($m->toStringTree())."\n";
		$s="";
		if ($c->hasMultipleVoices()) {
				if ($c->voice()>1) $s.=";";
				$s.="(".($this->reduce_nodes($m->nodes(),$c)).")";
		} else {
				$s.="|".($this->reduce_nodes($m->nodes(),$c));			
		}
		return $s;
	}

	function reduce_parallel($m,$c) {
		//print "reduce_measure: ".($m->toStringTree())."\n";
		$s="|";
		$index=1;
		foreach($m->nodes() as $ni) {
			$ci=$c->withVoice($index);
			$s.="".($this->reduce($ni,$ci))."";
			++$index;
		}

		return $s;
	}

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
		if ($m instanceof mm\TerminalNode) return $m;		
		if ($m instanceof mm\UnaryNode) return $this->reduce_pass_unary($m,$c);
		if ($m instanceof mm\BinaryNode) return $this->reduce_pass_binary($m,$c);	
		mm\err("unsupported node type: $m class:".get_class($m));
	}

	public static function create() {
		return new AbcTranslator();
	}

	function createContext() {
		return new AbcContext();
	}
}

?>