<?php
namespace nan\mm;
use nan\mm;
use nan\mm\TwelveTone;
use nan\mm\SevenTone;
use nan\mm\Tone;
use nan\mm\Value;
use nan\mm\Test;
use nan\mm\Chord;
use nan\mm\Octave;
use nan\mm\ChordProgression;
use nan\mm\Melody;
use nan\mm\Tempo;

require("autoloader.php");
include_once("midi_class_v178/classes/midi.class.php");

TwelveTone\Functions::Load;
SevenTone\Functions::Load;
Tone\Functions::Load;
Test\Functions::Load;
Value\Functions::Load;
Octave\Functions::Load;
Chord\Functions::Load;
ChordProgression\Functions::Load;
Midi\Functions::Load;
Melody\Functions::Load;
Tempo\Functions::Load;

function chordProgression1() {
	return ChordProgression\americanToChordProgression("D#maj7 Fm C7 F");
}

function melody1() {
	return Melody\americanToMelody("C#w Ch E0 G0 E1 O3 C#0 E0 G0 E0");
}

function melody2() {
	return Repeat::nw()
		->withTimes(4)
		->modify(MixNote::nw()
				->withTone(TwelveTone\FSharp)
				->modify(melody1())
			);
}

function voice1() {
	return Voice::nw()
		->withChordedNote(
			ChordedNote::nw()
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\FNatural)->withOctave(Octave\O3))
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\ENatural)->withOctave(Octave\O4))
				->withValue(Value\Whole)
		)
		->withChordedNote(
			ChordedNote::nw()
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\FNatural)->withOctave(Octave\O3))
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\ANatural)->withOctave(Octave\O4))
				->withValue(Value\Quarter)
		)
		->withChordedNote(
			ChordedNote::nw()
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\FNatural)->withOctave(Octave\O3))
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\ANatural)->withOctave(Octave\O2))
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\CNatural)->withOctave(Octave\O2))
				->withValue(Value\Quarter)
		)
		->withChordedNote(
			ChordedNote::nw()
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\FNatural)->withOctave(Octave\O3))
				->withPlacedTone(PlacedTone::nw()->withTone(TwelveTone\ANatural)->withOctave(Octave\O4))
				->withValue(Value\Whole)
		);
}

function testMelodyMidi() {	
	Midi\melodyToMidi(melody2());
}

function testVoiceMidi() {
	Midi\voiceToMidi(voice1());
}

function testAmerican() {
	Test\assertEquals("twelveToAmerican",TwelveTone\twelveToAmerican(TwelveTone\ASharp),"A#");
	Test\assertEquals("twelveToAmerican.rest",TwelveTone\twelveToAmerican(TwelveTone\Rest),"-");	
	Test\assertEquals("americanToTwelve",TwelveTone\americanToTwelve("A#"),TwelveTone\ASharp);
	Test\assertTrue("isSharp",TwelveTone\isSharp(TwelveTone\ASharp));	
	Test\assertFalse("isFlat",TwelveTone\isFlat(TwelveTone\ASharp));
	Test\assertFalse("isNatural",TwelveTone\isNatural(TwelveTone\ASharp));
	Test\assertEquals("sevenToneToAmerican",SevenTone\sevenToneToAmerican(TwelveTone\twelveToSeven(TwelveTone\ASharp)),"A");
	Test\assertEquals("sevenToneToAmerican.rest",SevenTone\sevenToneToAmerican(SevenTone\Rest),"-");
	Test\assertTrue("valueToDuration",Value\valueToDuration(Value\Half),1/2);
	Test\assertTrue("durationToValue",Value\durationToValue(1,Value\Whole));
	//Test\assertEquals("mixNote",MixNote::nw()->withTone(TwelveTone\DSharp)->modify(melody1()),"Cb D# C D#");
	Test\assertTrue("sevenTonePitch",SevenTone\sevenTonePitch(SevenTone\B),11);
	print "\namericanToChord:".Chord\americanToChord("D#maj7");
	print "\namericanToChordProgression:".chordProgression1();

}


function melody3() {
	 return Melody\americanToMelody("E0 F0 G0 A0 B0 D0");
}

function chordProgression3() {
	return ChordProgression\americanToChordProgression("D G D G");
}

function testChordToWaltz() {
	$voice=ChordProgression\ChordProgressionToVoice::nw()
		->withChordProgression(chordProgression3())
		->withChordToVoice(Chord\ChordToWaltz::nw())
		->toVoice();
	print "voice:$voice\n";
	$arrangement=Arrangement::nw()
		->withVoice($voice);
		//->withVoice(Melody\melodyToVoice(melody3() ));
	//Midi\voiceToMidi($voice);
	Midi\arrangementToMidi($arrangement,"midi/testChordToWaltz.mid");
}

function testMelodyToMidi() {
	Midi\melodyToMidi(Melody\americanToMelody("A0 B0 C0 A0 B0 C0 A0 B0 C0 A0 B0 C0 A0 B0 C0"),"midi/testMelodyToMidi.mid");
}

function testTempo() {
	$tempo=Tempo\Tempo::nw()
		->withBeatValue(Value\Quarter)
		->withBeatsPerMinute(120);
	print sprintf("tempo:%s",Tempo\tempoToCanonical($tempo));
}

function testTimeSignature() {
	$timeSignature=TimeSignature\TimeSignature::nw()
		->withPulseValue(3)
		->withPulseValue(Value\Quarter);

	print sprintf("timeSignature:%s",TimeSignature\timeSignatureToCanonical($timeSignature));
}

function test() {	
	//testTempo();
	//testTimeSignature();
	testChordToWaltz();
	testMelodyToMidi();
	//testAmerican();
	//testVoiceMidi();
	//Melody::nw();
	//print "americanToMelody:".americanToMelody("O3 C#w Cb1 O4 C1");
	//testMelodyMidi();
}

test();

?>