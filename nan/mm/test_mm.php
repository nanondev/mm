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
use nan\mm\Note;
use nan\mm\Dynamic\Attack;

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

function testTwelveToAmerican() {
	Test\assertEquals("twelveToAmerican",TwelveTone\twelveToAmerican(TwelveTone\ASharp),"A#");
}

function testTwelveToAmericanRest() {
	Test\assertEquals("twelveToAmerican.rest",TwelveTone\twelveToAmerican(TwelveTone\Rest),"-");	
}

function testAmericanToTwelve() {
	Test\assertEquals("americanToTwelve",TwelveTone\americanToTwelve("A#"),TwelveTone\ASharp);
}

function testIsSharp() {
	Test\assertTrue("isSharp",TwelveTone\isSharp(TwelveTone\ASharp));		
}

function testIsFlat() {
	Test\assertFalse("isFlat",TwelveTone\isFlat(TwelveTone\ASharp));
}

function testIsNatural() {
	Test\assertFalse("isNatural",TwelveTone\isNatural(TwelveTone\ASharp));
}

function testSevenToAmerican() {
	Test\assertEquals("sevenToneToAmerican",SevenTone\sevenToneToAmerican(TwelveTone\twelveToSeven(TwelveTone\ASharp)),"A");
}

function testSevenToneToAmericanRest() {
	Test\assertEquals("sevenToneToAmerican.rest",SevenTone\sevenToneToAmerican(SevenTone\Rest),"-");
}

function testValueToDuration() {
	Test\assertTrue("valueToDuration",Value\valueToDuration(Value\Half),1/2);
}

function testDurationToValue() {
	Test\assertTrue("durationToValue",Value\durationToValue(1,Value\Whole));	
}

function testSevenTonePitch() {
	Test\assertTrue("sevenTonePitch",SevenTone\sevenTonePitch(SevenTone\B),11);
}

function testAmerican() {
	//Test\assertEquals("mixNote",MixNote::nw()->withTone(TwelveTone\DSharp)->modify(melody1()),"Cb D# C D#");
	
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
	
	$arrangement=Arrangement::nw()		
		->withVoice($voice);
		//->withVoice(Melody\melodyToVoice(melody3() ));
	//Midi\voiceToMidi($voice);
	Midi\arrangementToMidi($arrangement,"midi/testChordToWaltz.mid");
}

function testChordToArpeggio() {
	$voice=ChordProgression\ChordProgressionToVoice::nw()
		->withChordProgression(chordProgression3())
		->withChordToVoice(Chord\ChordToArpeggio::nw()
			->withArpeggioTones([0,2])
			->withArpeggioTones([1])
			->withArpeggioTones([1,3])
			)
		->toVoice();
	
	$arrangement=Arrangement::nw()		
		->withVoice($voice);
		//->withVoice(Melody\melodyToVoice(melody3() ));
	//Midi\voiceToMidi($voice);
	Midi\arrangementToMidi($arrangement,"midi/testChordToArpeggio.mid");
}
function testDoubleMelody() {
	$doubleMelody=DoubleMelody::nw()
		->withMelody(Melody\americanToMelody("A0 A0 A0 A0 A0 A0 A0 A0"))
		->withOctave(Octave\O2)
		->toArrangement();
	
	$doubleMelody=Arrangement::nw()	
		->withVoice($doubleMelody->voices()[0]->withInstrument("Violin"))
		->withVoice($doubleMelody->voices()[1]->withInstrument("Acoustic Guitar (steel)"));
	
	Midi\arrangementToMidi($doubleMelody,"midi/testDoubleMelody.mid");
}

function testMelodyToMidi() {
	Midi\melodyToMidi(Melody\americanToMelody("A0 B0 C0 A0 B0 C0 A0 B0 C0 A0 B0 C0 A0 B0 C0"),"midi/testMelody.mid");
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

function testRest() {
	Midi\melodyToMidi(Melody\americanToMelody("A3 r1 D3 r1 A3 r1 D3 r1 A3 r1 D3 r1 A3"),"midi/testRest.mid");
}

function testAccent() {
	$melody=Melody\Melody::nw()
		->withNote(Note\Note::nw()
			->withPlacedTone(PlacedTone\PlacedTone::nw()
				->withTone(SevenTone\C)				
			)
		)
		->withNote(Note\Note::nw()
			->withPlacedTone(PlacedTone\PlacedTone::nw()
				->withTone(SevenTone\C)				
			)
		)
		->withNote(Note\Note::nw()
			->withPlacedTone(PlacedTone\PlacedTone::nw()
				->withTone(SevenTone\C)		
			)
			->withAttack(Attack\Accented)		
		);

	$melody=Repeat::nw()
		->withTimes(10)
		->withMelody($melody)
		->toMelody();
	Midi\melodyToMidi($melody,"midi/testAccent.mid");
}

function test() {	
	testTwelveToAmerican();
	testTwelveToAmericanRest();
	testAmericanToTwelve();
	testIsSharp();
	testIsFlat();
	testIsNatural();
	testSevenToAmerican();	
	testSevenToneToAmericanRest();
	testValueToDuration();
	testDurationToValue();
	testSevenTonePitch();
	testRest();
	testAccent();
	testDoubleMelody();
	testTempo();
	testTimeSignature();
	testChordToWaltz();
	testChordToArpeggio();
	testMelodyToMidi();
	testAmerican();
	testVoiceMidi();
	print "americanToMelody:".americanToMelody("O3 C#w Cb1 O4 C1");	
}

test();

?>