<?php
namespace nan\mm;
use nan\mm\TwelveTone;

require_once("autoloader.php");

class Repeat extends MelodyModifier {
	var $times=1;
	
	static function nw() {
		return new Repeat();
	}

	function times() {
		return $this->times;
	}

	function withTimes($times) {
		$mixNote=clone $this;
		$mixNote->times=$times;
		return $mixNote;
	}

	function modify($melody) {
		$retMelody=Melody::nw();
		for($i=1;$i<=$this->times+1;$i++)  {
			foreach($melody->notes() as $note) {
				$retMelody=$retMelody->withNote($note);
			}
		}
		return $retMelody;
	}
}

?>