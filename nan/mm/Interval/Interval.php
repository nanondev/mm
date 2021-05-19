<?php
namespace nan\mm\Interval;
use nan\mm;
use nan\mm\TwelveTone;
use nan\mm\SevenTone;

TwelveTone\Functions::Load;
SevenTone\Functions::Load;

class Functions { const Load=1; }


const Unison=50000;
const MinorSecond=50001;
const MajorSecond=50002;
const MinorThird=50003;
const MajorThird=50004;
const PerfectFourth=50005;
const PerfectFifth=50006;
const AugmentedFourth=50007;
const MinorSixth=50008;
const MajorSixth=50009;
const MinorSeventh=50010;
const MajorSeventh=50011;
const PerfectOctave=50012;

const IntervalSemitones=array(
	Unison=>0,
	MinorSecond=>1,
	MajorSecond=>2,
	MinorThird=>3,
	MajorThird=>4,
	PerfectFourth=>5,
	AugmentedFourth=>6,
	PerfectFifth=>7,
	MinorSixth=>8,
	MajorSixth=>9,
	MinorSeventh=>10,
	MajorSeventh=>11,
	PerfectOctave=>12
);

const IntervalToneDistance=array(
	Unison=>0,
	MinorSecond=>1,
	MajorSecond=>1,
	MinorThird=>2,
	MajorThird=>2,
	PerfectFourth=>3,
	AugmentedFourth=>3,
	PerfectFifth=>4,
	MinorSixth=>5,
	MajorSixth=>5,
	MinorSeventh=>6,
	MajorSeventh=>6,
	PerfectOctave=>7
);

function intervalSemitones($interval) {
	return IntervalSemitones[$interval];
}

function twelveAddInterval($twelveTone,$interval) {
	$sevenTone=TwelveTone\twelveToSeven($twelveTone);
	$toneDistance=IntervalToneDistance[$interval];
	$sevenToneIndex=SevenTone\ToneToIndex[$sevenTone];
	$newSevenTone=SevenTone\IndexToTone[($sevenToneIndex+$toneDistance)%7];
	return TwelveTone\sevenToTwelve($newSevenTone);
}

?>