<?php
namespace nan\mm\Tempo;
use nan\mm;
use nan\mm\Value;

class Functions { const Load=1; }

Value\Functions::Load;

class Tempo {
	var $beatsPerMinute=60;
	var $beatValue=Value\Quarter;

	static function nw() {
		return  new Tempo();
	}

	function beatValue() {
		return $this->beatValue;
	}

	function withBeatValue($beatValue) {
		$tempo=clone $this;
		$tempo->beatValue=$beatValue;
		return $tempo;
	}

	function beatsPerMinute() {
		return $this->beatsPerMinute;
	}

	function withBeatsPerMinute($withBeatsPerMinute) {
		$tempo=clone $this;
		$tempo->withBeatsPerMinute=$withBeatsPerMinute;
		return $tempo;
	}

	function __toString() {
		tempoToCanonical(this);
	}
}

function tempoToCanonical($tempo) {	
	return sprintf("Tempo V%s=%s",
		Value\valueToCanonical($tempo->beatValue()),
		$tempo->beatsPerMinute());
}


?>
