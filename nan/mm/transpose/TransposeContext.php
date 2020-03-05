<?php
namespace nan\mm\transpose;
use nan\mm;
use nan\mm\reduce;

class TransposeContext {
	var $transposeDistance;
	function __construct($transposeDistance=0) {
		$this->transposeDistance=$transposeDistance;
	}

	function withTransposeDistance($transposeDistance) {
		return new TransposeContext($transposeDistance);
	}
	function transposeDistance() {
		return $this->transposeDistance;
	}
	function __toString() {
		return sprintf("AbcContext <tr:%s>",$this->transposeDistance);
	}
}

?>