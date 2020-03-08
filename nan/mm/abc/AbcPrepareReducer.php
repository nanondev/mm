<?php

namespace nan\mm\abc;
use nan\mm;
use nan\mm\reduce;

class AbcPrepareReducer extends reduce\ChainReducer {
	public static function create() {

		$r=(new AbcPrepareReducer())
			->withReducer(new reduce\ArpReducer())
			->withReducer(new reduce\MultiplexReducer())
			->withReducer(new reduce\Up8thMulReducer())
			->withReducer(new reduce\MeasureReducer())
			->withReducer(new reduce\TransposeReducer())
			->withReducer(new reduce\MergeReducer());

		return $r;
	}
}


?>