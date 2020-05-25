<?php
namespace nan\mm\reduce;
use nan\mm;

class ChordReducer extends NodeReducer {
	function reduceChord($m,$c) {
		$chordNotes=$m->notes();
		$chordMerged=mm\list_to_merge($chordNotes);		
		return $chordMerged;
	}
}

?>