<?php
namespace nan\mm\abc;
use nan\mm;
use nan\mm\reduce;

class AbcNs {}

class AbcContext extends reduce\MeasureContext {
	var $voice=0;

	function hasMultipleVoices() {
		return $this->voice!=0;
	}

	function withVoice($index) {
		$m=clone $this;
		$m->voice=$index;
		return $m;
	}

	function voice() {
		return $this->voice;
	}
}

class StringContext  {}

class StringReducer {
	function reduce_nodes($nodes,$c) {
		$s="";
		if (!is_array($nodes)) warn("wrong argument: $nodes");
		foreach ($nodes as $mi) {
			$s.=$this->reduce($mi);
		}
		return $s;	
	}

	function reduce($m,$c=null) {
		if ($c==null) {
			$c=$this->createContext();
		}
		$fn="reduce_".($m->name());
		if (!method_exists($this,$fn)) {
			$fn="reduce_pass";
		}
		$s=$this->$fn($m,$c);
		return $s;
	}

	function createContext() {
		return new StringContext();
	}

}

class AbcPrepareReducer extends reduce\ChainReducer {
	public static function create() {

		$r=(new AbcPrepareReducer())
			->withReducer(new reduce\ArpReducer())
			->withReducer(new reduce\MultiplexReducer())
			->withReducer(new reduce\Up8thMulReducer())
			->withReducer(new reduce\MeasureReducer())
			->withReducer(new reduce\TransposeReducer())
			->withReducer(new reduce\MergeReducer());

		return $r;
	}
}

class AbcTranslator extends StringReducer {
	function reduce_tempo($m,$c) {
		$nodes=$m->nodes();	
		$n=$m->beatNote();
		$m=$m->beatsByMinute();
		print "TEMP: $n,$m\n";	
		$s="T:$n $m\n";
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

	function reduce_pass($m,$c) {
		return $this->reduce_nodes($m->nodes,$c);
	}

	public static function create() {
		return new AbcTranslator();
	}

	function createContext() {
		return new AbcContext();
	}
}
 
class AbcReducer extends StringReducer {
	function reduce($m,$c=null) {
		if ($c==null) $c=$this->createContext();

		$prepareReducer=AbcPrepareReducer::create();
		$translator=AbcTranslator::create();

		$mp=$prepareReducer->reduce($m);
		$abc=$translator->reduce($mp);
		return $abc;
	}	
}

function abc2midi($abcfile) {
	$abc2midi_runner="\"C:\\Program Files (x86)\\runabc\\abc2midi.exe\"";
	$abc2midi_cmd="$abc2midi_runner $abcfile -o $abcfile.midi";
	$out=array();
	$ret=-1;
	mm\debug("abc2midi cmd: $abc2midi_cmd");
	$res=exec($abc2midi_cmd,$out,$ret);
	//print_r($out);
}

function abc_store($abcstr,$abcfile) {
	file_put_contents($abcfile,$abcstr);
}

?>
