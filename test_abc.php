<?php
// http://abcnotation.com/wiki/abc:standard:v2.1

namespace nan\mm\test;

use nan\mm;
use nan\mm\abc;
use nan\mm\reduce;
use nan\mm\test;

require_once("autoloader.php");

new mm\MmNs();
new abc\ABCNs();
new test\TestNs();
//new mm\ChordNs();

function assert_abc_equals($title,$m,$abc) {
	$pass=false;
	$r=new abc\AbcTranslator(); // new abc\AbcReducer();
	$pass=assert_equals($title,$r->reduce($m),$abc);
	if (!$pass) {
		$mp=$r->reduce($m);
		print "failed-node-tree:".($m->toStringTree())."\n";
		print "failed-node-prepared-tree:".($mp->toStringTree())."\n";
		exit();
	}	
}

function test_abctranslator_then() {
	assert_abc_equals("test_abctranslator_then",mm\notes("AB"),"AB");
}


function test_abctranslator_natural() {
	assert_abc_equals("test_abctranslator_natural",mm\notes("A=B"),"A=B");
}

function test_abctranslator_flat() {
	assert_abc_equals("test_abctranslator_flat",mm\notes("A_B"),"A_B");
}

function test_abctranslator_fraction() {
	assert_abc_equals("test_abctranslator_fraction",mm\notes("A/2"),"A/2");
}

function test_abctranslator_fraction_2() {
	assert_abc_equals("test_abctranslator_fraction_2",mm\notes("A/3"),"A/3");
}

function test_abctranslator_time_1() {
	assert_abc_equals("test_time_1",
		mm\Time::nw(3,4,mm\notes("ABCDEF")),"M:3/4\n|ABC");
}

function test_abctranslator_time_2() {
	assert_abc_equals("test_time_2",
		then::nw()
			->withFirstNode(mm\Time::nw(3,4,mm\notes("ABC")))
			->withSecondNode(mm\Time::nw(4,4,mm\notes("ABCD")))	
		,"M:3/4\n|ABC|DEF|M:4/4|ABCD|DEFG");
}
function main() {
	test_abctranslator_then();
	test_abctranslator_natural();
	test_abctranslator_flat();
	test_abctranslator_fraction();
	test_abctranslator_fraction_2();
	test_abctranslator_time_1();
	test_abctranslator_time_2();
/*	test_up8th01();
	test_up8th02();
	test_up8th03();
	test_up8th04();
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
	test_rep();
	test_tempo();
	//test_mask();
	//test_up8thmul();*/
}

print main();

?>