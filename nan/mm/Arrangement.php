<?php
namespace nan\mm;
use nan\mm\Tempo;

class Arrangement {
	var $voices=[];
	var $tempo;
	var $timeSignature;

	static function nw() {
		$arrangement= new Arrangement();
		$tempo=Tempo\Tempo::nw();
		$arrangement->tempo=$tempo;
		return $arrangement;
	}

	function voices() {
		return $this->voices;
	}

	function withVoice($voice) {
		$arrangement=clone $this;
		$arrangement->voices[]=$voice;
		return $arrangement;
	}
	
	function tempo() {
		return $this->tempo;
	}

	function withTempo($tempo) {
		$arr=clone $this;
		$arr->tempo=$tempo;
		return $arr;
	}

	function timeSignature() {
		return $this->timeSignature;
	}

	function withTimeSignature($timeSignature) {
		$arr=clone $this;
		$arr->timeSignature=$timeSignature;
		return $arr;
	}

	function __toString() {
		return arrangementToCanonical($this);
	}	
}

function arrangementToCanonical($arrangement) {	
	$s="Arrangement";
	foreach($arrangement->voices() as $voice) {
		$s.=" voice:$voice";
	}
	return $s;
}

?>