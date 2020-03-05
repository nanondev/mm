<?php
namespace nan\mm;

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

	static function nw($note,$duration=1,$accidentalModifier=self::ACCIDENTAL_MODIFIER_NONE) {
		return new note($note,$duration,$accidentalModifier);
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

?>