<?php
namespace nan\mm\reduce;
use nan\mm;
namespace nan\mm\reduce;
use nan\mm;


class MeasureReducer extends NodeReducer {	

	function nextMeasure($notes,$c) {
		$measure_complete=false;
		$index=0;
		$measure_size=2;		
		$measure_load=0;
		if (count($notes)==0) return null;

		do {
			$note=$notes[$index];
			$note_duration=$note->duration();
			$measure_load=$measure_load+$note_duration;
			$measure_complete=$measure_load>=$measure_size;
		} while(!$measure_complete && $index++<count($notes));

		if($measure_complete) {	
			$measure_partial=$measure_load>$measure_size;
			if ($measure_partial) { // quitamos una note.
				--$index;
			}
			$measure_notes=array_slice($notes,0,$index+1);
			$then=mm\list_to_then($measure_notes);
			$measure=mm\Measure::nw($then); 
			return $measure;
		}
	}

	function reduceThen($m,$c) {
		$notes=mm\then_to_list($m);
		$notes_left=$notes;
		$measures=[];
		do {
			$m=$this->nextMeasure($notes_left,$c);
			$notes_left=array_slice($notes_left,mm\then_note_count($m));
			if ($m!=null) {
				$measures[]=$m;
			}
		} while($m!=null);
		return mm\list_to_then($measures);
	}

	function reduceMeasure($m,$c) { 
		return $m; 
	}

	function reduceTime($m,$c) { 		
		$co=$c->withTime($m);
		return $this->reducePass($m,$co); 
	}

	function createContext() {
		return new MeasureContext();
	}

}

?>