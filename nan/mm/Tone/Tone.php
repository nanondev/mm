<?php
namespace nan\mm\Tone;
use nan\mm;
use nan\mm\SevenTone;
use nan\mm\TwelveTone;

SevenTone\Functions::Load;
TwelveTone\Functions::Load;

class Functions { const Load=1; }

function isRest($tone) {
	if (TwelveTone\isTwelveTone($tone)) return TwelveTone\isRest($tone);
	if (SevenTone\isSevenTone($tone)) return SevenTone\isRest($tone);
	throw new \Exception("isRest: FAIL tone:$tone msg:unknown tone");
}

function toneToAmerican($tone) {
	if (TwelveTone\isTwelveTone($tone)) return TwelveTone\twelveToAmerican($tone);
	if (SevenTone\isSevenTone($tone)) return SevenTone\tonalToAmerican($tone);
	throw new \Exception("toneToAmerican: FAIL tone:$tone msg:unknown tone");
}

function americanToTone($american) {
	return TwelveTone\americanToTwelveTone($american);
}

?>