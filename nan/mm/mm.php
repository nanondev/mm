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
 * - precondicion de reducciones? no debería, ver como preservar algebra  
 * - implementar mascaras
 * - eliminar NSs
 */
namespace nan\mm;

$debug_enabled=true;
$warn_enabled=true;

class MmNs {}


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
	throw new \exception($fullMsg);
 }
