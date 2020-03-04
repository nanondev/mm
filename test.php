<?php
// http://abcnotation.com/wiki/abc:standard:v2.1

namespace nan\mm;

use nan\mm;
use nan\mm\abc;
use nan\mm\measure;
use nan\mm\transpose;
use nan\mm\arp;
use nan\mm\chord;

require_once("autoloader.php");

new mm\MmNs();
new abc\ABCNs();
new measure\MeasureNs();
new reduce\ReduceNs();
new arp\ArpNs();
new chord\ChordNs();

function assert_equals($title,$a,$b) {
	if ($a==$b) {
		print "[PASS] $title\n";
		return true;
	} else {
		print "[FAIL] $title ($a != $b)\n";
		return false;
	}
}

function assert_abc_equals($title,$m,$abc) {
	$pass=assert_equals($title,(new abc\AbcReducer())->reduce($m),$abc);
	if (!$pass) {
		$r=new abc\AbcPrepareReducer();
		$mp=$r->reduce($m);
		print "failed-node-tree:".($m->toStringTree())."\n";
		print "failed-node-prepared-tree:".($mp->toStringTree())."\n";
	}	
}

function test_note_then() {
	assert_abc_equals("test_note_then",notes("AB"),"|AB");
}

function test_note_sharp() {
	assert_abc_equals("test_note_sharp",notes("A^B"),"|A^B");
}

function test_note_natural() {
	assert_abc_equals("test_note_natural",notes("A=B"),"|A=B");
}

function test_note_fraction() {
	assert_abc_equals("test_note_fraction",notes("A/2"),"|A/2");
}

function test_note_fraction_2() {
	assert_abc_equals("test_note_fraction_2",notes("A/3"),"|A/3");
}

function test_note_flat() {
	assert_abc_equals("test_note_flat",notes("A_B"),"|A_B");
}

function test_measure_44() {
	assert_abc_equals("test_measure_44",notes("ABCDEFGA"),"|ABCD|EFGA");	
}

function test_measure_factions() {
	assert_abc_equals("test_measure_fractions",notes("A/2B/2CDEABCD"),"|A/2B/2CDE|ABCD");	
}

function test_measure_44_2() {
	assert_abc_equals("test_measure_44_2",notes("E2F2G3DE4F4")->wrap(new time(4,4)),"M:4/4\n|E2F2|G3D|E4|F4");	
}

function test_measure_34() {
	assert_abc_equals("test_measure_34",notes("ABCDEFGA2")->wrap(new time(3,4)),"M:3/4\n|ABC|DEF|GA2");	
}

function test_merge() {
	assert_abc_equals("test_merge",
		(new merge())->addNode(notes("ABCD"))->addNode(notes("DEFG"))
	,"|(ABCD);(DEFG)");	
}

function test_merge_2() {
	assert_abc_equals("test_merge_2",
		(new merge())->addNode(notes("ABCDEFGA"))->addNode(notes("DEFGABCD"))
	,"|(ABCD);(DEFG)|(EFGA);(ABCD)");	
}

function test_merge_3() {
	assert_abc_equals("test_merge_3",
		(new merge())->addNode(notes("D4EFGA"))->addNode(notes("DEFGA4"))
	,"|(D4);(DEFG)|(EFGA);(A4)");	
}

function test_up8th() {
	assert_abc_equals("test_up8th",notes("ABCDEFGA")->wrap(new up8th()),"|A'B'C'D'|E'F'G'A'");	
}

function test_down8th() {
	assert_abc_equals("test_down8th",notes("ABCDEFGA")->wrap(new down8th()),"|A,B,C,D,|E,F,G,A,");	
}

function test_arp() {
	assert_abc_equals("test_arp",new arp([0,1,2,0],8,chord\amchord("C")),"|CEGC|CEGC");
}

function test_arp_2() {
	assert_abc_equals("test_arp_2",(new arp([2,1,0,1,2,0],9,chord\amchord("Dm")))->wrap(new time(3,4)),"M:3/4\n|AFD|FAD|AFD");		
}

function test_chord_Dm() {
	assert_abc_equals("test_chord_Dm",
		(new then())->withNodes(chord\amchord("Dm")->nodes()),"|DFA");
}

function test_chord_D() {
	assert_abc_equals("test_chord_D",
		(new then())->withNodes(chord\amchord("D")->nodes()),"|D^FA");
}

function test_time_1() {
	assert_abc_equals("test_time_1",
		(new time(3,4))->addNode(notes("ABCDEF")),"M:3/4\n|ABC|DEF");
}

function test_time_2() {
	assert_abc_equals("test_time_2",
		(new then())
			->addNode((new time(3,4))->addNode(notes("ABCDEF")))
			->addNode((new time(4,4))->addNode(notes("ABCDDEFG")))			
		,"M:3/4\n|ABC|DEF|M:4/4|ABCD|DEFG");
}

function test_multiplex() {
assert_abc_equals("test_multiplex",
		(new multiplex\multiplex(2))
			->addNode(notes("ABCD"))			
		,"|(ABCD;ABCD)");

}

function main() {
	test_note_then();
	test_note_sharp();
	test_note_natural();
	test_note_flat();
	test_note_fraction();
	test_note_fraction_2();
	test_time_1();
	test_time_2();
	test_up8th();
	test_down8th();
	test_measure_44();
	test_measure_factions();
	test_measure_44_2();
	test_measure_34();
	test_merge();
	test_merge_2();
	test_merge_3();
	test_arp();
	test_arp_2();
	test_chord_Dm();
	test_chord_D();
	test_multiplex();
}

print main();

?>