<?php 
namespace nan\mm\reduce;
use nan\mm;

class ArpReducer extends NodeReducer {
	function reduce_arp($m,$c) {
		$chord=$m->chord();
		$chordNotes=$chord->nodes();

		$length=$m->lengthInNotes();
		$orderPattern=$m->orderPattern();
		$notes=[];
		for ($i=0;$i<$length;$i++) {
			$patternIndex=$orderPattern[$i%count($orderPattern)];
			$note=$chordNotes[$patternIndex%count($chordNotes)];
			$notes[]=mm\note::nw($note);
		}
		return mm\then::nw($notes);
	}
}

 ?>
