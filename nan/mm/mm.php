<?php
/*
 * PENDIENTE:
 * - completar casos de test para lo ya hecho
 * - notacion de acordes
 * - precondicion de reducciones? no debería, ver como preservar algebra  
 * - implementar mascaras
 * - eliminar NSs
 * - determinar primitivas: cuales operadores son primitivos?
 */
namespace nan\mm;

$debug_enabled=true;
$warn_enabled=true;

class MmNs {}


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
	throw new \exception($fullMsg);
 }
