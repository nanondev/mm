<?php
namespace nan\mm\Midi;
use nan\mm;
use nan\mm\Pitch;
use nan\mm\Octave;
use nan\mm\Value;
use nan\mm\Arrangement;
use nan\mm\Melody;
use nan\mm\Tone;
use nan\mm\Dynamic\Attack;

Melody\Functions::Load;
Pitch\Functions::Load;
Tone\Functions::Load;
Attack\Functions::Load;

class Functions { const Load=1; }

const MidiC4=60;

function placedToneToMidiNote($placedTone) {
	$octaveMidiNote=MidiC4+(Octave\octaveIndex($placedTone->octave())-4)*12;
	$midiNote=$octaveMidiNote+Pitch\tonePitch($placedTone->tone());
	return $midiNote;
}

class ArrangementToMidi {
	var $arrangements=array();
	var $midiWholeDuration=400;

	var $arrangementStartTime=0;
	var $voiceTime=array();
	var $voiceNoteIndex=array();
	var $midi;
	var $maxTime=0;
	var $midiFileName="midi/testMidi.mid";
	var $midiQueue=array();
	var $activeTimeSignature;
	var $activeBeat;

	function applyBeatAccent($note) {
		
	}

	function __construct() {
		$this->arrangement=Arrangement::nw();
	}

	static function nw() {
		return new ArrangementToMidi();
	}

	function arrangements() {
		return $this->arrangements;
	}

	function withArrangement($arrangement) {
		$toMidi=clone $this;
		$toMidi->arrangements[]=$arrangement;		
		return $toMidi;
	}

	function withMidiFileName($midiFileName) {
		$toMidi=clone $this;
		$toMidi->midiFileName=$midiFileName;
		return $toMidi;
	}

	function midiFileName() {
		return $this->midiFileName;
	}
	
	function setupStructs($arrangement) {
		$voiceIndex=0;
		foreach($arrangement->voices() as $voice) {
			$this->voiceTime[$voiceIndex]=0;
			$this->voiceNoteIndex[$voiceIndex]=0;		
			++$voiceIndex;		
		}
	}

	function setupMidi() {
		$ticksPerBeat=100;
		$this->midi=new \Midi();
		$this->midi->open($ticksPerBeat);
		$this->midi->newTrack();
	}

	function closeMidi() {
		$globalMaxTime=$this->maxTime;
		$msgTrkEnd=sprintf("%s Meta TrkEnd",$globalMaxTime+500);
		//print "msgTrkEnd:$msgTrkEnd\n";
		$this->midi->addMsg(0, $msgTrkEnd);
		$xml=$this->midi->getXml();		
		$this->midi->saveMidFile($this->midiFileName);
		//print "midi_xml:$xml\n";
	}

	function pickVoice($arrangement) {
		$voiceIndex=0;
		$minVoiceTime=PHP_INT_MAX;
		$minVoiceIndex=-1;		

		foreach($arrangement->voices() as $voice) {
			$notesCount=count($voice->chordedNotes());
			$noteIndex=$this->voiceNoteIndex[$voiceIndex];
			$inRange=$noteIndex<$notesCount;
			//print "dg-pickVoice picking: voiceIndex:$voiceIndex inRange ??? noteIndex:$noteIndex<notesCount:$notesCount? $inRange\n";
			if ($inRange) { // if we inRange ??? didn't reach the final note
				$time=$this->voiceTime[$voiceIndex];
				$better=$time<=$minVoiceTime;
				//print "dg-picking voiceTime:$time<=minVoiceTime:$minVoiceTime ? $better \n";
				if ($better) {
					$minVoiceIndex=$voiceIndex;
					$minVoiceTime=$this->voiceTime[$voiceIndex];
				}
			}
			++$voiceIndex;
		}
		//print "dg-pickVoice: minVoiceIndex:$minVoiceIndex\n";
		return $minVoiceIndex;
	}

	function queueMidiMessage($track,$clazz,$time,$message) {
		$this->midiQueue[]=array($time,$clazz,$message);	
	}
	
	function flushMidiQueue() {
		foreach ($this->midiQueue as $timedMessage) {
			$time=$timedMessage[0];
			$clazz=$timedMessage[1];
			$message=$timedMessage[2];
			$this->midi->addMsg(0, $message);
			print "dg-message:$message\n";
		}			
	}

	function sortMidiQueue() {
		usort($this->midiQueue,array('\\nan\\mm\\Midi\\MidiCompare','midiMessageCompare'));	
	}
	

	function chordedNoteGain($chordedNote,$volume) {
		return Attack\attackGain($chordedNote->attack(),$volume);	
	}

	function generateVoiceNote($arrangement,$voiceIndex) {
		$noteIndex=$this->voiceNoteIndex[$voiceIndex];
		$voice=$arrangement->voices()[$voiceIndex];
		$chordedNote=$voice->chordedNotes()[$noteIndex];
		$time=$this->voiceTime[$voiceIndex];
		$timeDelta=$this->midiWholeDuration*Value\valueToDuration($chordedNote->value());
		print "chordedNote:".$chordedNote."\n";
		foreach($chordedNote->placedTones() as $placedTone) {
			$globalTime=$this->arrangementStartTime+$time;
			$globalTimeOff=$this->arrangementStartTime+$time+$timeDelta;
			if (!Tone\isRest($placedTone->tone())) {
				$midiNote=placedToneToMidiNote($placedTone);			
				//print "*** dg-generateVoiceIndex: voiceIndex:$voiceIndex noteIndex:$noteIndex time:$time globalTime:$globalTime $globalTimeOff\n";
				$midiChannel=$voiceIndex+1;
				$volume=80;
				$volume=$this->chordedNoteGain($chordedNote,$volume);
				print "apply gain: $volume\n";
				$midiMsgOn="$globalTime On ch=$midiChannel n=$midiNote v=$volume";
				$midiMsgOff="$globalTimeOff Off ch=$midiChannel n=$midiNote v=$volume";			
				$this->queueMidiMessage(0,"On",$globalTime,$midiMsgOn);				
				$this->queueMidiMessage(0,"Off",$globalTimeOff,$midiMsgOff);
			}			
		}		
		if ($time>$this->maxTime) $this->maxTime=$time+$timeDelta;
		$this->voiceTime[$voiceIndex]=$time+$timeDelta;		
		$this->voiceNoteIndex[$voiceIndex]=$this->voiceNoteIndex[$voiceIndex]+1;
	}

	function writeArrangementNotes($arrangement) {
		$voiceCount=count($arrangement->voices());
		$this->maxTime=0;			
		do {
			$voiceIndex=$this->pickVoice($arrangement);
			$hasNext=$voiceIndex!=-1;
			if ($hasNext) $this->generateVoiceNote($arrangement,$voiceIndex);						
		} while($hasNext);
		$this->arrangementStartTime=$this->maxTime;
	}

	function writeVoiceInstrumentMidi($arrangement) {
		$voiceIndex=0;
		foreach($arrangement->voices() as $voice) {
			$globalTime=$this->arrangementStartTime;
			$channel=$voiceIndex+1;
			$instrumentMsg = sprintf("$globalTime Meta InstrName \"%s\"",$voice->instrument());
			$programNumber=$this->midi->findGm1InstrumentPatchNumber($voice->instrument());
			$programChangeMsg=sprintf("$globalTime PrCh ch=$channel p=$programNumber");
			$this->queueMidiMessage(0,"Meta",$globalTime,$instrumentMsg);
			$this->queueMidiMessage(0,"PrCh",$globalTime,$programChangeMsg);
			++$voiceIndex;
		}	
	}

	function writeArrangementMidi($arrangement) {
		$this->setupStructs($arrangement);
		//$this->midi->setBpm($arrangement->tempo()->beatsPerMinute());
		$this->writeVoiceInstrumentMidi($arrangement);
		$this->writeArrangementNotes($arrangement);		
	}
	
	function writeArrangementsMidi() {		
		foreach($this->arrangements as $arrangement) {
			$this->writeArrangementMidi($arrangement);
		}		
	}

	function toMidi() {
		$this->setupMidi();			
		$this->writeArrangementsMidi();
		$this->sortMidiQueue();
		$this->flushMidiQueue();
		$this->closeMidi();
	}

	function totalTime() {
		return $this->maxTime;
	}

}

/*
	algoritmo: 
	-mientras hay notas sin procesar: 
		-elegir proxima voz (la primera que ya terminó de ejecutar)
		-generar nota de proxima voz
		-aumentar contador de tiempo de la voz		
*/
function arrangementToMidi($arrangement,$midiFileName) {
	$toMidi=ArrangementToMidi::nw()
		->withMidiFileName($midiFileName)
		->withArrangement($arrangement);
	$toMidi->toMidi();
	return $toMidi;
}

function arrangementsToMidi($arrangements,$midiFileName) {
	$toMidi=ArrangementToMidi::nw()
		->withMidiFileName($midiFileName);

	foreach ($arrangements as $arrangement) {		
		$toMidi=$toMidi->withArrangement($arrangement);
	}

	$toMidi->toMidi();
	return $toMidi;
}

function voiceToMidi($voice,$midiFileName) {
	return
		ArrangementToMidi::nw()
		->withMidiFileName($midiFileName)
		->withArrangement(
			Arrangement::nw()
				->withVoice($voice)
			)
		->toMidi();
}

function melodyToMidi($melody,$midiFileName) {
	return voiceToMidi(Melody\melodyToVoice($melody),$midiFileName);
}

function textToFileName($text) {
	return str_ireplace(" ","-",$text);
}

function albumTrackToMidi($album,$track,$trackIndex) {
		$midiTrackFileName=textToFileName(sprintf("albums/%s/midi/%s-%s-%s.mid",
				$album->title(),
				$album->title(),
				$trackIndex,
				$track->title()));
		
		$toMidi=arrangementsToMidi($track->arrangements,$midiTrackFileName);
		echo sprintf("albumTrackToMidi: midiTrackFileName: $midiTrackFileName totalTime:%s msg: MIDI file wrote\n",$toMidi->totalTime());
}

function albumTracksToMidi($album) {
	$trackIndex=1;
	foreach($album->tracks() as $track) {
		albumTrackToMidi($album,$track,$trackIndex);
		++$trackIndex;
	}
}

function albumSingleTrackToMidi($album) {
	$midiAlbumFileName=textToFileName(sprintf("albums/%s/midi/%s.mid",$album->title(),$album->title()));

	$toMidi=ArrangementToMidi::nw()
		->withMidiFileName($midiAlbumFileName);

	foreach($album->tracks() as $track) {
		foreach ($track->arrangements() as $arrangement) {
			$toMidi=$toMidi->withArrangement($arrangement);
		}		
	}
	
	echo "albumToMidi: albumFile: $midiAlbumFileName msg: MIDI file wrote\n";
	
	return $toMidi->toMidi();
}

function albumToMidi($album) {
	albumTracksToMidi($album);
	albumSingleTrackToMidi($album);
}

?>