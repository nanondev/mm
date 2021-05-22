<?php
namespace nan\mm;
use nan\mm\TwelveTone;
use nan\mm\Melody;

require_once("autoloader.php");

class DoubleMelody extends Melody\MelodyToArrangement {
	var $octave;

	static function nw() {
		return new DoubleMelody();
	}

	function otave() {
		return $this->octave;
	}

	function withOctave($octave) {
		$double=clone $this;
		$double->octave=$octave;
		return $double;
	}

	function toArrangement() {
		$arrangement=Arrangement::nw();

		$originalVoice=Voice\Voice::nw();
		$doubleVoice=Voice\Voice::nw();
		foreach($this->melody->notes() as $note) {
			$originalVoice=$originalVoice->withNote($note);
			$doubleNote=$note->withPlacedTone($note->placedTone()->withOctave($this->octave));
			$doubleVoice=$doubleVoice->withNote($doubleNote);
		}
				
		$arrangement=$arrangement
			->withVoice($originalVoice)
			->withVoice($doubleVoice);

		return $arrangement;
	}
}

?>