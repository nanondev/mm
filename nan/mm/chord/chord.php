<?php 
namespace nan\mm\chord;
use nan\mm;
use nan\mm\reduce;

class ChordNs {}

class chord extends mm\MusicNode {
	function __construct($nodes=[]) {
		parent::__construct("chord",$nodes);
	}


	static function american($name) {
		$notes=[];
		$base=["C"=>0,"D"=>1,"E"=>2,"F"=>3,"G"=>4,"A"=>5,"B"=>6];
		$baseInv=[0=>"C",1=>"D",2=>"E",3=>"F",4=>"G",5=>"A","B"=>6];
		
		
		preg_match("/([_=^]?)([ABCDEFG])(m?)/",$name,$chord_match);
		
		$accidentalModifier=mm\note::ACCIDENTAL_MODIFIER_NONE;
		if ($chord_match[1]=="_") $accidentalModifier=-1;
		if ($chord_match[1]=="=") $accidentalModifier=0;
		if ($chord_match[1]=="^") $accidentalModifier=1;
		
		$fundamental=$chord_match[2];
		$isMinor=strlen($chord_match[2])>0;

		$fundamentalIndex=$base[$fundamental];
		
		$notes[]=new mm\note($fundamental,1,$accidentalModifier);
		$notes[]=new mm\note($baseInv[$fundamentalIndex+2],1);
		$notes[]=new mm\note($baseInv[$fundamentalIndex+4],1);
		$m=new chord($notes);
		return $m;
	}
}

function amchord($name) {
	return chord::american($name);
}
?>