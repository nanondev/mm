<?php
namespace nan\mm\reduce;
use nan\mm;
namespace nan\mm\reduce;
use nan\mm;


class MeasureReducer extends NodeReducer {	

	function nextMeasure($notes,$c) {
		if (count($notes)==0) return null;
		mm\debug("MeasureReducer: nextMeasure: notes:".mm\list2str($notes));
		$measure_complete=false;
		$index=0;
		$measure_size=$c->time()->quantity();		
		$measure_load=0;
		$notes_count=count($notes);
		do {
			$note=$notes[$index];
			$note_duration=$note->duration();
			$measure_load=$measure_load+$note_duration;
			$measure_complete=$measure_load>=$measure_size;
			//print "note:$note note_duration: $note_duration measure_complete:$measure_complete measure_size:$measure_size index:$index notes_count:$notes_count\n";
		} while(!$measure_complete && ++$index<count($notes));

		$measure_partial=$measure_load>$measure_size;
		if ($measure_partial) { // quitamos una note.
			--$index;
		}
		$measure_notes=array_slice($notes,0,$index+1);
		$then=mm\list_to_then($measure_notes);
		$measure=mm\Measure::nw($then); 

		if($measure_complete) {	
			mm\debug("MeasureReducer: nextMeasure: built measure: $measure");
			return $measure;
		} else {
			mm\warn("MeasureReducer: nextMeasure: measure incomplete (measure_load: $measure_load < measure_size: $measure_size)");
			return $measure;
		}
	}

	function reduceNote($m,$c) {
		mm\debug("MeasureReducer: reduceNote: m:".$m->toStringTree());
		$notes=[$m];
		$measure=$this->nextMeasure($notes,$c);
		return $measure;
	}

	function reduceThen($m,$c) {
		mm\debug("MeasureReducer: reduceThen: m:".$m->toStringTree());
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