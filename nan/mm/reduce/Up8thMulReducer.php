<?php
namespace nan\mm\reduce;
use nan\mm;

class Up8thMulReducer extends NodeReducer {
	function reduce_Up8thMul($m,$c) {		
		$mo=mm\merge::nw($m->nodes());	
		$mo=$mo->addNode(mm\up8th::nw($m->nodes()));
		return $mo;
	}
}
