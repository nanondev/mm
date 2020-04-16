<?php 
namespace nan\mm;

class ChordNs {}

class Chord extends TerminalNode {
	var $notes;

	function __construct($notes=[]) {
		parent::__construct();
		$this->notes=$notes;
	}

	static function nw($notes=[]) {
		return new Chord($notes);
	}

	function notes() {
		return $this->notes;
	}
	
	static function american($name) {
		$notes=[];
		$base=["C"=>0,"D"=>1,"E"=>2,"F"=>3,"G"=>4,"A"=>5,"B"=>6];
		$baseInv=[0=>"C",1=>"D",2=>"E",3=>"F",4=>"G",5=>"A","B"=>6];
		
		
		preg_match("/([_=^]?)([ABCDEFG])(m?)/",$name,$chord_match);
		
		$accidentalModifier=note::ACCIDENTAL_MODIFIER_NONE;
		if ($chord_match[1]=="_") $accidentalModifier=-1;
		if ($chord_match[1]=="=") $accidentalModifier=0;
		if ($chord_match[1]=="^") $accidentalModifier=1;
		
		$fundamental=$chord_match[2];
		$isMinor=strlen($chord_match[2])>0;

		$fundamentalIndex=$base[$fundamental];
		
		$notes[]=note::nw($fundamental,1,$accidentalModifier);
		$notes[]=note::nw($baseInv[$fundamentalIndex+2],1);
		$notes[]=note::nw($baseInv[$fundamentalIndex+4],1);
		$m=chord::nw($notes);
		return $m;
	}

	static function clazz() {
		return get_class([Note::nw("C")]);
	}

	function toStringAttributes() {
		$notesStr=$this->toStringList($this->notes);
		return "notes:$notesStr";
	}
}

function amchord($name) {
	return chord::american($name);
}

?>