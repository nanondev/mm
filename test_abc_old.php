<?php

function test_note_sharp() {
	assert_abc_equals("test_note_sharp",mm\notes("A^B"),"|A^B");
}

function test_note_natural() {
	assert_abc_equals("test_note_natural",mm\notes("A=B"),"|A=B");
}

function test_note_flat() {
	assert_abc_equals("test_note_flat",mm\notes("A_B"),"|A_B");
}

function test_note_fraction() {
	assert_abc_equals("test_note_fraction",mm\notes("A/2"),"|A/2");
}

function test_note_fraction_2() {
	assert_abc_equals("test_note_fraction_2",mm\notes("A/3"),"|A/3");
}

function test_measure_44() {
	assert_abc_equals("test_measure_44",mm\notes("ABCDEFGA"),"|ABCD|EFGA");	
}

function test_measure_factions() {
	assert_abc_equals("test_measure_fractions",mm\notes("A/2B/2CDEABCD"),"|A/2B/2CDE|ABCD");	
}

function test_measure_44_2() {
	assert_abc_equals("test_measure_44_2",mm\notes("E2F2G3DE4F4")->wrap(time::nw(4,4)),"M:4/4\n|E2F2|G3D|E4|F4");	
}

function test_measure_34() {
	assert_abc_equals("test_measure_34",mm\notes("ABCDEFGA2")->wrap(time::nw(3,4)),"M:3/4\n|ABC|DEF|GA2");	
}

function test_merge() {
	assert_abc_equals("test_merge",
		(new merge())->addNode(mm\notes("ABCD"))->addNode(mm\notes("DEFG"))
	,"|(ABCD);(DEFG)");	
}

function test_merge_2() {
	assert_abc_equals("test_merge_2",
		(new merge())->addNode(mm\notes("ABCDEFGA"))->addNode(mm\notes("DEFGABCD"))
	,"|(ABCD);(DEFG)|(EFGA);(ABCD)");	
}

function test_merge_3() {
	assert_abc_equals("test_merge_3",
		(new merge())->addNode(mm\notes("D4EFGA"))->addNode(mm\notes("DEFGA4"))
	,"|(D4);(DEFG)|(EFGA);(A4)");	
}

function test_up8th() {
	assert_abc_equals("test_up8th",notes("ABCDEFGA")->wrap(new up8th()),"|A'B'C'D'|E'F'G'A'");	
}

function test_down8th() {
	assert_abc_equals("test_down8th",notes("ABCDEFGA")->wrap(new down8th()),"|A,B,C,D,|E,F,G,A,");	
}

function test_arp() {
	assert_abc_equals("test_arp",new arp([0,1,2,0],8,chord::american("C")),"|CEGC|CEGC");
}

function test_arp_2() {
	assert_abc_equals("test_arp_2",(new arp([2,1,0,1,2,0],9,chord::american("Dm")))->wrap(time::nw(3,4)),"M:3/4\n|AFD|FAD|AFD");		
}

function test_chord_Dm() {
	assert_abc_equals("test_chord_Dm",
		then::nw()->withNodes(chord::american("Dm")->nodes()),"|DFA");
}

function test_chord_D() {
	assert_abc_equals("test_chord_D",
		then::nw()->withNodes(chord::american("D")->nodes()),"|D^FA");
}

function test_time_1() {
	assert_abc_equals("test_time_1",
		time::nw(3,4,mm\notes("ABCDEF")),"M:3/4\n|ABC|DEF");
}

function test_time_2() {
	assert_abc_equals("test_time_2",
		then::nw()
			->addNode(time::nw(3,4)->addNode(mm\notes("ABCDEF")))
			->addNode(time::nw(4,4)->addNode(mm\notes("ABCDDEFG")))			
		,"M:3/4\n|ABC|DEF|M:4/4|ABCD|DEFG");
}

function test_multiplex() {
	assert_abc_equals("test_multiplex",
		multiplex::nw(2)->addNode(mm\notes("ABCD"))			
		,"|(ABCD;ABCD)");

}

function test_rep() {
	assert_abc_equals("test_rep",
		rep::nw(2)->addNode(notes("ABCD"))			
		,"|ABCD|ABCD");
}

function test_tempo() {
	assert_abc_equals("test_tempo",
		tempo::nw(1,128)->addNode(notes("ABCD"))			
		,"Q:1=128\n|ABCD");
}

function test_up8thmul() {
	return assert_abc_equals("test_up8thmul",Up8thMul::nw(notes("ABCD")),"|(ABCD;A'B'C'D)");
}

function test_up8th2() {
	$r=new reduce\TransposeReducer();
	$r=new reduce\Up8thReducer();
	print "reducido:".$r->reduce(then::nw(
		Up8th::nw([notes("AB"),notes("CD")])
	))->toStringTree();
}

function test_up8th01() {
	$r=new reduce\TransposeReducer();
	$r=new reduce\Up8thReducer();
	assert_equals("test:up8th01",$r->reduce(
		Up8th::nw(mm\note::nw("A")
	)),"A'");
}


function test_up8th02() {
	$r=new reduce\TransposeReducer();
	$r=new reduce\Up8thReducer();
	assert_equals("test:up8th02",$r->reduce(
		Up8th::nw(mm\then::nw(mm\note::nw("A"),mm\note::nw("B")
	))),"A'B'");
}

function test_up8th03() {
	$r=new reduce\TransposeReducer();
	$r=new reduce\Up8thReducer();
	assert_equals("test:up8th03",$r->reduce(
		Up8th::nw(mm\parallel::nw(mm\note::nw("A"),mm\note::nw("B")
	))),"A'&B'");
}

function test_up8th04() {
	$r=new reduce\TransposeReducer();
	$r=new reduce\Up8thReducer();
	assert_equals("test:up8th04",$r->reduce(
		Up8th::nw(
			mm\then::nw(
				mm\parallel::nw(mm\note::nw("A"),mm\note::nw("B")),
				mm\parallel::nw(mm\note::nw("C"),mm\note::nw("D"))
			)
		)),"[[A'B'][C'D']]");
}

?>