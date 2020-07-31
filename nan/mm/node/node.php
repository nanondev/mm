<?php
namespace nan\mm\node;
use nan\mm;

class NodeNs {}

function notes($s) {
	$firstNode=null;
	$pattern="/([_=^]?[ABCDEFG](\/?)[0-9]?)/";
	$matches=array();	
	preg_match_all($pattern,$s,$matches);	

	foreach(array_reverse($matches[0]) as $match) {
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
		
		$newNode=note::nw($note,$duration,$accidentalModifier);		
		if ($firstNode==null) {
			$firstNode=$newNode;
		} else {
			$firstNode=then::nw($newNode,$firstNode);
		}
	}
	return $firstNode;
}



function then_to_list($m) {
	$nodes=[];
	return then_to_list_rec($m,$nodes);
}

function then_to_list_rec($m,&$nodes) {	
	if ($m instanceof BinaryNode) {
		then_to_list_rec($m->firstNode(),$nodes);
		then_to_list_rec($m->secondNode(),$nodes);
	} else if ($m instanceof UnaryNode) {
		then_to_list_rec($m->uniqueNode(),$nodes);
	} else if ($m instanceof Note) {
		$nodes[]=$m;
	}
	return $nodes;
}

function list_to_then($ms) {		
	$first=null;
	for($i=count($ms)-1;$i>=0;$i--) {
		$mi=$ms[$i];
		if ($first==null) {
			$first=$mi;
		} else  {
			$first=Then::nw($mi,$first);
		}
	}
	return $first;
}

function list_to_merge($ms) {		
	$first=null;
	for($i=count($ms)-1;$i>=0;$i--) {
		$mi=$ms[$i];
		if ($first==null) {
			$first=$mi;
		} else  {
			$first=Merge::nw($mi,$first);
		}
	}
	return $first;
}

function then_note_count($m) {	
	if ($m instanceof BinaryNode) {
		return then_note_count($m->firstNode())
			+then_note_count($m->secondNode());
	} else if ($m instanceof UnaryNode) {
		return then_note_count($m->uniqueNode());
	} else if ($m instanceof Note) {
		return 1;
	}
	return 0;
}

?>