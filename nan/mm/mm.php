<?php
/*
 * PENDIENTE:
 * HECHO - soporte e tags en nodos
 * HECHO - implementar: down8th, up8th
 * HECHO - measure: debería tener en cuenta longitud de notas 
 * HECHO - mejorar arquitectura de reducciones: debería ser pipeline ?
 * HECHO - measure: deberia usar reduce
 * HECHO - abc: separar reduce y measure ? esta desordenado así como está
 * HECHO soporte de notas fraccionadas (sub-negras)
 * HECHO - implementar merge (voces simultaneas)
 * - nombres de clase: respetar case
 * - completar casos de test para lo ya hecho
 * - notacion de acordes
 * - precondicion de reducciones  
 */
namespace nan\mm;

$debug_enabled=true;
$warn_enabled=true;

class MmNs {}

class MusicNodeTag {
	var $transposeDistance;
	function __construct($transposeDistance=0) {
		$this->transposeDistance=$transposeDistance;
	}

	function withTransposeDistance($transposeDistance) {
		return new MusicNodeTag($transposeDistance);
	}
	function transposeDistance() {
		return $this->transposeDistance;
	}
	function __toString() {
		if ($this->transposeDistance!=0) {
			return sprintf("<tr:%s>",$this->transposeDistance);
		} else {
			return "";
		}
	}
}

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
			err("unique node expected, but many found: $m");
		} else if (count($this->nodes())==0) {
			err("unique node expected, but none found: $m");
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

class time extends MusicNode {
	var $quantity;
	var $duration;

	function __construct($quantity,$duration,$nodes=[]) {
		parent::__construct("time",$nodes);
		$this->quantity=$quantity;
		$this->duration=$duration;
	}

	function quantity() {
		return $this->quantity;
	}
	function duration() {
		return $this->duration;
	}

	function  toStringCompact() {
		return sprintf("(%s/%s)(%s)%s",$this->quantity,$this->duration,$this->toStringNodes(),$this->tag());
	}
}

class measure extends MusicNode {

	function __construct($nodes) {
		parent::__construct("measure",$nodes);
	}

	function toStringCompact() {
		return "|".($this->toStringNodes()).$this->tag();
	}
}


class then extends MusicNode {

	function __construct($nodes=[]) {
		parent::__construct("then",$nodes);
	}

	function toStringCompact() {
		return "".($this->toStringNodes());
	}
	function toStringSeparator() {
		return "";
	}
}

class header extends MusicNode {
	var $header;
	function __construct($header,$nodes=[]) {
		parent::__construct("header",$nodes);
		$this->header=$header;
	}

	function header() {
		return $this->header;
	}

	function toStringCompact() {
		return sprintf("<%s>:\n%s\n",join(', ',$this->header),($this->toStringNodes()));
	}
}

class merge extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("merge",$nodes);
	}

	function toStringCompact() {
		return "".($this->toStringNodes()); //dejamos simplemente los corchetes.
	}

	function toStringSeparator() {
		return "";
	}
}

class note extends MusicNode {
	var $note;
	var $duration;
	var $accidentalModifier;
	const ACCIDENTAL_MODIFIER_NONE=-999;

	function __construct($note,$duration=1,$accidentalModifier=self::ACCIDENTAL_MODIFIER_NONE) {
		parent::__construct("note");		
		$this->note=$note;
		$this->duration=$duration;
		$this->accidentalModifier=$accidentalModifier;
	}

	function note() {
		return $this->note;
	}

	function duration() {
		return $this->duration;
	}

	function isSharp() {
		return $this->accidentalModifier==1;
	}

	function sharpPrefix() {
		return "^";
	}

	function isFlat() {
		return $this->accidentalModifier==-1;
	}

	function flatPrefix() {
		return "_";
	}

	function isNatural() {
		return $this->accidentalModifier==0;
	}

	function naturalPrefix() {
		return "=";
	}

	function hasAccidentals() {
		return $this->isFlat() || $this->isNatural() || $this->isSharp();
	}

	function accidental() {
		if ($this->isFlat()) return $this->flatPrefix();
		if ($this->isSharp()) return $this->sharpPrefix();
		if ($this->isNatural()) return $this->naturalPrefix();
		return "";
	}

	function toStringDuration() {
		$d=$this->duration;
		for($i=2;$i<=64;$i++) {
			if ($d==1/$i) return "/$i";		
		}
		return $d==1 ? "" : "".$d;
	}

	function toStringComplementary() {
		$durationStr = $this->duration>1 ? $this->duration : "";
		return sprintf("%s%s",$this->accidental(),$this->note,$durationStr);
	}

	function toStringCompact() {
		$durationStr = $this->duration>1 ? $this->duration : "";
		return sprintf("%s%s%s%s",$this->accidental(),$this->note,$durationStr,$this->tag());
	}
}

class rep extends MusicNode {
	var $reps;
	function __construct($reps,$nodes=[]) {
		parent::__construct("rep",$nodes);
		$this->reps=$reps;
	}

	function reps() {
		return $this->reps;
	}
	function  toStringCompact() {
		return sprintf("%s*%s",$this->reps,$this->toStringNodes());
	}

	function toStringSeparator() {
		return "";
	}
}

class up8th extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("up8th",$nodes);		
	}

	function  toStringCompact() {
		return sprintf("8th+%s",$this->toStringNodes());
	}
}

class down8th extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("down8th",$nodes);		
	}

	function  toStringCompact() {
		return sprintf("8th-%s",$this->toStringNodes());
	}
}

class key extends MusicNode	 {
	var $reps;
	function __construct($key,$nodes=[]) {
		parent::__construct("key",$nodes);
		$this->key=$key;
	}

	function key() {
		return $this->key;
	}
	function  toStringCompact() {
		return sprintf("%s:%s",$this->key,$this->toStringNodes());
	}

	function toStringSeparator() {
		return "";
	}
}

class tempo extends MusicNode {
	var $beatNote,$beatsByMinute;
	function __construct($beatNote,$beatsByMinute,$nodes=[]) {
		parent::__construct("tempo",$nodes);
		$this->beatNote;
		$this->beatsByMinute;
	}
	function beatNote() {
		return $this->beatNote;
	}
	function beatsByMinute() {
		return $this->beatsByMinute;
	}
}

class parallel extends MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("parallel",$nodes);
	}
}

class arp extends MusicNode {
	var $orderPattern;
	var $lengthInNotes;

	function __construct($orderPattern,$lengthInNotes,$nodes=[]) {
		parent::__construct("arp",$nodes);
		$this->orderPattern=$orderPattern;
		$this->lengthInNotes=$lengthInNotes;
	}	

	function chord() {
		$chord=$this->uniqueNode();
		return $chord;
	}

	function orderPattern() {
		return $this->orderPattern;
	}

	function lengthInNotes() {
		return $this->lengthInNotes;
	}

}

function notes($s) {
	$nodes=array();
	$pattern="/([_=^]?[ABCDEFG](\/?)[0-9]?)/";
	$matches=array();	
	preg_match_all($pattern,$s,$matches);	
	foreach($matches[0] as $match) {
		$note_match=array();

		preg_match("/([_=^]?)([ABCDEFG])(\/?)([0-9]?)/",$match,$note_match);
		
		$accidentalModifier=note::ACCIDENTAL_MODIFIER_NONE;
		if ($note_match[1]=="_") $accidentalModifier=-1;
		if ($note_match[1]=="=") $accidentalModifier=0;
		if ($note_match[1]=="^") $accidentalModifier=1;
		$note=$note_match[2];
		$is_fraction=strlen($note_match[3])>0;
		$duration=$note_match[4];
		if (strlen($duration)==0) $duration="1";
		$duration=$is_fraction ? 1/intval($duration) : intval($duration);
		
		$nodes[]=new note($note,$duration,$accidentalModifier);		
	}
	return new then($nodes);
}

function merge($m1,$m2) {
	return new merge(array($m1,$m2));
}

function warn($msg) {
	global $warn_enabled;
	if ($warn_enabled) {
		echo "warning:$msg\n";		
	}
}

function debug($msg){ 
	global $debug_enabled;
	$debug_enabled=false;
	if ($debug_enabled) {
		echo "debug: $msg\n";
	}
}

function err($msg) {
	$fullMsg="error: $msg\n";
	throw $fullMsg;
 }
