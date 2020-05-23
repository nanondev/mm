<?php
namespace nan\mm\test;
use nan\mm;

class TestNs {}

function list2str($arr) {
	$s="";
	foreach ($arr as $v) {
		if (strlen($s)>0) $s.=",";
		$s.="".$v;
	}
	return $s;
}

function assert_todo($title) {
	print "[TODO] $title\n";
}
function assert_equals($title,$a,$b) {
	if ($a==$b) {
		print "[PASS] $title\n";
		return true;
	} else {
		$aStr=$a;
		$bStr=$b;
		if (is_array($a)) {
			$aStr=list2str($a);
		}
		if (is_array($b)) {
			$bStr=list2str($b);
		}
		if ($aStr==$bStr) {
			print "[PASS] $title\n";
		} else {
			print "[FAIL] $title ($aStr != $bStr)\n";
		}
		return false;
	}
}

function assert_tree_equals($title,$m,$str) {
	assert_equals($title,$m->ToStringTree(),$str);
}

function assert_compact_equals($title,$m,$str) {
	assert_equals($title,$m->ToStringCompact(),$str);
}
?>