<?php
namespace nan\mm\TwelveTone;
use nan\mm;
use nan\mm\SevenTone;
use nan\mm\Interval;
SevenTone\Functions::Load;
Interval\Functions::Load;

class Functions { const Load=1; }

const ANatural=20001;
const ASharp=20002;
const AFlat=20003;
const BNatural=20004;
const BSharp=20005;
const BFlat=20006;
const CNatural=20007;
const CSharp=20008;
const CFlat=20009;
const DNatural=20010;
const DSharp=20011;
const DFlat=20012;
const ENatural=20013;
const ESharp=20014;
const EFlat=20015;
const FNatural=20016;
const FSharp=20017;
const FFlat=20018;
const GNatural=20019;
const GSharp=20020;
const GFlat=20021;	
const Rest=20022;

const TwelveSharps=array(ASharp,BSharp,CSharp,DSharp,ESharp,FSharp,GSharp);
const TwelveFlats=array(AFlat,BFlat,CFlat,DFlat,EFlat,FFlat,GFlat,AFlat);
const TwelveNaturals=array(ANatural,BNatural,CNatural,DNatural,ENatural,FNatural,GNatural);
const TwelveToneSet=array(ASharp,BSharp,CSharp,DSharp,ESharp,FSharp,GSharp,AFlat,BFlat,CFlat,DFlat,EFlat,FFlat,GFlat,AFlat,ANatural,BNatural,CNatural,DNatural,ENatural,FNatural,GNatural,Rest);

const TwelveToAmerican=array(
	ANatural=>"A",
	ASharp=>"A#",
	AFlat=>"Ab",
	BNatural=>"B",
	BSharp=>"B#",
	BFlat=>"Bb",
	CNatural=>"C",
	CSharp=>"C#",
	CFlat=>"Cb",
	DNatural=>"D",
	DSharp=>"D#",
	DFlat=>"Db",
	ENatural=>"E",
	ESharp=>"E#",
	EFlat=>"Eb",
	FNatural=>"F",
	FSharp=>"F#",
	FFlat=>"Fb",
	GNatural=>"G",
	GSharp=>"G#",
	GFlat=>"Gb",
	Rest=>"r",
);

const TwelveTonePitch=array(
	BSharp=>0,
	CNatural=>0,
	CSharp=>1,
	DFlat=>1,
	DNatural=>2,
	DSharp=>3,
	EFlat=>3,
	ENatural=>4,
	FFlat=>4,
	ESharp=>5,
	FNatural=>5,
	FSharp=>6,
	GFlat=>6,
	GNatural=>7,
	GSharp=>8,
	AFlat=>8,
	ANatural=>9,
	ASharp=>10,
	BFlat=>11,
	BNatural=>11,
	CFlat=>11
);

const TwelveToTonal=array(
	ANatural=>SevenTone\A,
	ASharp=>SevenTone\A,
	AFlat=>SevenTone\A,
	BNatural=>SevenTone\B,
	BSharp=>SevenTone\B,
	BFlat=>SevenTone\B,
	CNatural=>SevenTone\C,
	CSharp=>SevenTone\C,
	CFlat=>SevenTone\C,
	DNatural=>SevenTone\D,
	DSharp=>SevenTone\D,
	DFlat=>SevenTone\D,
	ENatural=>SevenTone\E,
	ESharp=>SevenTone\E,
	EFlat=>SevenTone\E,
	FNatural=>SevenTone\F,
	FSharp=>SevenTone\F,
	FFlat=>SevenTone\F,
	GNatural=>SevenTone\G,
	GSharp=>SevenTone\G,
	GFlat=>SevenTone\G,
	Rest=>SevenTone\Rest
);

const SevenToTwelve=array(
	SevenTone\A=>ANatural,
	SevenTone\B=>BNatural,
	SevenTone\C=>CNatural,
	SevenTone\D=>DNatural,
	SevenTone\E=>ENatural,
	SevenTone\F=>FNatural,
	SevenTone\G=>GNatural,
	SevenTone\Rest=>Rest
);

function checkTwelveTone($tone) {
	$in=in_array($tone,TwelveToneSet,true);	
	if (!$in) {
		print "checkTwelveTone: FAIL tone: $tone\n";
		throw new \Exception("checkTwelveTone: FAIL tone: $tone");
	}
}

function isFlat($tone) 
{
	checkTwelveTone($tone);
	return in_array($tone,TwelveFlats,true);
}

function isRest($tone) 
{
	checkTwelveTone($tone);
	return $tone==Rest;
}

function isSharp($tone) 
{
	checkTwelveTone($tone);
	return in_array($tone,TwelveSharps,true);
}

function isNatural($tone)
{
	checkTwelveTone($tone);
	return in_array($tone,TwelveNaturals,true);
}

function isTwelveTone($tone) {
	return in_array($tone,TwelveToneSet);
}

function twelveToSeven($tone)
{
	checkTwelveTone($tone);
	return TwelveToTonal[$tone];
}

function sevenToTwelve($tone)
{
	SevenTone\checkSevenTone($tone);
	return SevenToTwelve[$tone];
}

function twelveToAmerican($tone) {
	checkTwelveTone($tone);
	return TwelveToAmerican[$tone];
}

function americanToTwelve($american) {	
	$americanToTwelve=array_flip(TwelveToAmerican);
	if (!array_key_exists($american,$americanToTwelve)) throw new \Exception("americanToTwelve: american:'$american' msg: unknown tone");
	return $americanToTwelve[$american];	
}

function twelveTonePitch($tone) {
	checkTwelveTone($tone);
}

function twelveAddInterval($twelveTone,$interval) {
	$sevenTone=twelveToSeven($twelveTone);
	$toneDistance=Interval\IntervalToneDistance[$interval];
	$sevenToneIndex=SevenTone\ToneToIndex[$sevenTone];
	$newSevenTone=SevenTone\IndexToTone[($sevenToneIndex+$toneDistance)%7];	
	return sevenToTwelve($newSevenTone);
}


?>