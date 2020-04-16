<?php
namespace nan\mm\reduce;
use nan\mm;

class Up8thMulReducer extends NodeReducer {
	function reduce_Up8thMul($m,$c) {		
		$mo=mm\merge::nw($m->nodes())
			->addNode(mm\up8th::nw(mm\then::nw($m->nodes())));

		print "Nodo8mul:".$mo->toStringTree()."\n";
		return $mo;
	}
}
