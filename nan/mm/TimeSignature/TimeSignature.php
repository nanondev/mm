<?php

namespace nan\mm\TimeSignature;
use nan\mm;
use nan\mm\Value;

class Functions { const Load=1; }

Value\Functions::Load;

class TimeSignature {
	var $pulses=4;
	var $pulseValue=Value\Quarter;

	static function nw() {
		return  new TimeSignature();
	}

	function pulseValue() {
		return $this->pulseValue;
	}

	function withPulseValue($pulseValue) {
		$signature=clone $this;
		$signature->pulseValue=$pulseValue;
		return $signature;
	}

	function pulses() {
		return $this->pulses;
	}

	function withPulses($pulses) {
		$signature=clone $this;
		$signature->pulses=$pulses;
		return $signature;
	}

	function __toString() {
		return timeSignatureToCanonical(this);
	}
}

function timeSignatureToCanonical($timeSignature) {	
	return sprintf("TimeSignature %s/V%s",
		$timeSignature->pulses(),
		Value\valueToCanonical($timeSignature->pulseValue())
		);
}


?>