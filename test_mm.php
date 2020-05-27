<?php
namespace nan\mm\test;
use nan\mm;
use nan\mm\reduce;
use nan\mm\test;

require_once("autoloader.php");

new mm\MmNs();
new test\TestNs();

function test_note_build() {
	assert_tree_equals("note_A",mm\Note::nw("A"),"Note<note:A accidental:none transposeDistance:0 duration:1>");
	assert_compact_equals("note_B",mm\Note::nw("B"),"B");
	assert_compact_equals("note_C",mm\Note::nw("C"),"C");
	assert_compact_equals("note_D",mm\Note::nw("D"),"D");
	assert_compact_equals("note_E",mm\Note::nw("E"),"E");
	assert_compact_equals("note_F",mm\Note::nw("F"),"F");
	assert_compact_equals("note_G",mm\Note::nw("G"),"G");
}

function test_note_accidentals() {
	assert_compact_equals("note_flat",mm\Note::nw("_A"),"_A");
	assert_compact_equals("note_natural",mm\Note::nw("=A"),"=A");
	assert_compact_equals("note_sharp",mm\Note::nw("^A"),"^A");
}

function test_note_transposeDistance() {
	$m=mm\Note::nw("C");
	assert_compact_equals("note_transposeDistance1",$m->withTransposeDistance(0),"C");
	assert_compact_equals("note_transposeDistance2",$m->withTransposeDistance(12),"C'");
	assert_compact_equals("note_transposeDistance3",$m->withTransposeDistance(24),"C''");
	assert_compact_equals("note_transposeDistance4",$m->withTransposeDistance(-12),"C,");
	assert_compact_equals("note_transposeDistance5",$m->withTransposeDistance(-24),"C,,");
	assert_compact_equals("note_transposeDistance6",$m->withTransposeDistance(2),"C<tr:+2>");
}

function test_then() {
	assert_tree_equals("then_tree",
		mm\Then::nw(mm\Note::nw("C"),mm\Note::nw("D")),"Then[C D]");
	assert_compact_equals("then_first",
		mm\Then::nw(mm\Note::nw("C"),mm\Note::nw("D"))->firstNode(),"C");
	assert_compact_equals("then_second",
		mm\Then::nw(mm\Note::nw("C"),mm\Note::nw("D"))->secondNode(),"D");
}

function test_parallel() {
	assert_tree_equals("parallel_tree",
		mm\Parallel::nw(mm\Note::nw("C"),mm\Note::nw("D")),"Parallel[C D]");
	assert_compact_equals("parallel_first",
		mm\Parallel::nw(mm\Note::nw("C"),mm\Note::nw("D"))->firstNode(),"C");
	assert_compact_equals("parallel_second",
		mm\Parallel::nw(mm\Note::nw("C"),mm\Note::nw("D"))->secondNode(),"D");
}

function test_merge() {
	assert_tree_equals("merge_tree",
		mm\Merge::nw(mm\Note::nw("C"),mm\Note::nw("D")),"Merge[C D]");
	assert_compact_equals("merge_first",
		mm\Merge::nw(mm\Note::nw("C"),mm\Note::nw("D"))->firstNode(),"C");
	assert_compact_equals("merge_second",
		mm\Merge::nw(mm\Note::nw("C"),mm\Note::nw("D"))->secondNode(),"D");
}

function test_up8th() {
	assert_tree_equals("up8th_tree",
		mm\Up8th::nw(mm\Note::nw("C")),"Up8th[C]");
	assert_compact_equals("up8th_uniqueNode",
		mm\Up8th::nw(mm\Note::nw("C"))->uniqueNode(),"C");
}

function test_down8th() {
	assert_tree_equals("down8th_tree",
		mm\Down8th::nw(mm\Note::nw("C")),"Down8th[C]");
	assert_compact_equals("down8th_uniqueNode",
		mm\Down8th::nw(mm\Note::nw("C"))->uniqueNode(),"C");
}

function test_rep() {
	assert_tree_equals("rep_tree",
		mm\Rep::nw(3,mm\Note::nw("C")),"Rep<reps:3>[C]");
	assert_compact_equals("rep_uniqueNode",
		mm\Rep::nw(3,mm\Note::nw("C"))->uniqueNode(),"C");
}

function test_time() {
	assert_tree_equals("time_tree",
		mm\Time::nw(3,4,mm\Note::nw("C")),"Time<quantity:3,duration:4>[C]");
	assert_compact_equals("time_uniqueNode",
		mm\Time::nw(3,4,mm\Note::nw("C"))->uniqueNode(),"C");
}

function test_key() {
	assert_tree_equals("test_key_1",
		mm\Key::nw("C",mm\Note::nw("C")),"Key<key:C>[C]");
	assert_tree_equals("test_key_2",
		mm\Key::nw("G",mm\Note::nw("C")),"Key<key:G>[C]");
	assert_compact_equals("time_uniqueNode",
		mm\Key::nw("G",mm\Note::nw("C"))->uniqueNode(),"C");
}

function test_tempo() {
	assert_tree_equals("tempo_tree",
		mm\Tempo::nw(4,60,mm\Note::nw("C")),"Tempo<beatNote:4,beatsByMinute:60>[C]");
	assert_compact_equals("tempo_uniqueNode",
		mm\Tempo::nw(4,60,mm\Note::nw("C"))->uniqueNode(),"C");
}

function test_measure() {
	$m=mm\Measure::nw(mm\Note::nw("C"));
	assert_tree_equals("measure_tree",$m,"Measure[C]");	
	assert_compact_equals("measure_uniqueNode",$m->uniqueNode(),"C");
}

function test_multiplex() {
	$m=mm\Multiplex::nw(3,mm\Note::nw("C"));
	assert_tree_equals("multiplex_tree",$m,"Multiplex<channels:3>[C]");	
	assert_compact_equals("multiplex_uniqueNode",$m->uniqueNode(),"C");
}

function test_header() {
	$m=mm\Header::nw(["author"=>"nan"],mm\Note::nw("C"));
	assert_tree_equals("header_tree",$m,"Header<header:author='nan'>[C]");	
	assert_compact_equals("header_uniqueNode",$m->uniqueNode(),"C");
}

function test_notes() {
	new mm\MmNs();
	assert_compact_equals("notes_c",mm\notes("C"),"C");	
	assert_compact_equals("notes_csharp",mm\notes("^C"),"^C");	
	assert_compact_equals("notes_cnatural",mm\notes("=C"),"=C");	
	assert_compact_equals("notes_cflat",mm\notes("_C"),"_C");	
	assert_compact_equals("notes_csharp2",mm\notes("^C2"),"^C2");	
	assert_tree_equals("notes_cd",mm\notes("CD"),"Then[C D]");	
	assert_tree_equals("notes_cdef",mm\notes("CDEF"),"Then[C Then[D Then[E F]]]");	
}

function test_arp() {
	$m=mm\Arp::nw([0,1,2],3,mm\Chord::american("C"));
	assert_tree_equals("arp_tree",$m,"Arp<orderPattern:[0,1,2],lengthInNotes:3,chord:Chord<notes:[C,E,G]>>");	
}	

function test_chord() {
	$m=mm\Chord::nw([mm\notes("C"),mm\notes("E"),mm\notes("G")]);
	assert_tree_equals("chord_tree",$m,"Chord<notes:[C,E,G]>");	
	assert_equals("test_chord_Dm",
		mm\Chord::american("Dm")->notes(),["D","F","A"]);
	assert_equals("test_chord_D",
		mm\Chord::american("D")->notes(),["D","^F","A"]);
	assert_equals("test_chord_C",
		mm\Chord::american("C")->notes(),["C","E","G"]);
	assert_equals("test_chord_Cm",
		mm\Chord::american("Cm")->notes(),["C","_E","G"]);
}

function test_nodes() {
	test_note_build();
	test_note_accidentals();
	test_note_transposeDistance();
	test_then();
	test_parallel();
	test_merge();
	test_up8th();
	test_down8th();
	test_rep();
	test_time();
	test_tempo();
	test_measure();
	test_multiplex();
	test_header();
	test_notes();
	test_chord();
	test_arp();
	test_key();
}

function test_multiplexreducer_1() {
	$m=mm\Multiplex::nw(3,mm\notes("C"));
	$r=new reduce\MultiplexReducer();
	assert_tree_equals("test_multiplexreducer_1",$r->reduce($m),"Merge[C Merge[C C]]");		
}

function test_multiplexreducer_2() {
	$m=mm\Multiplex::nw(2,mm\notes("CD"));
	$r=new reduce\MultiplexReducer();
	assert_tree_equals("test_multiplexreducer_2",$r->reduce($m),"Merge[Then[C D] Then[C D]]");		
}

function test_multiplexreducer_3() {
	$m=mm\Multiplex::nw(2,mm\Merge::nw(mm\Multiplex::nw(2,mm\notes("AB")),mm\notes("D")));
	$r=new reduce\MultiplexReducer();
	assert_tree_equals("test_multiplexreducer_3",$r->reduce($m),"Merge[Merge[Merge[Then[A B] Then[A B]] D] Merge[Merge[Then[A B] Then[A B]] D]]");
}

function test_measurereducer_1() {
	$m=mm\Time::nw(2,4,mm\notes("ABCD"));
	$r=new reduce\MeasureReducer();
	assert_tree_equals("test_measureReducer",$r->reduce($m)->uniqueNode(),"Then[Measure[Then[A B]] Measure[Then[C D]]]");
}

function test_measurereducer_2() {
	$m=mm\Time::nw(4,4,mm\notes("ABCD"));
	$r=new reduce\MeasureReducer();
	assert_tree_equals("test_measureReducer_2",$r->reduce($m)->uniqueNode(),"Measure[Then[A Then[B Then[C D]]]]");
}

function test_measurereducer_3() {
	$m=mm\Time::nw(4,4,mm\notes("A"));
	$r=new reduce\MeasureReducer();
	assert_tree_equals("test_measureReducer_3",$r->reduce($m)->uniqueNode(),"Measure[A]");
}

function test_chordreducer() { 
	$m=mm\Chord::american("D");
	$r=new reduce\ChordReducer();
	assert_tree_equals("tetest_chordreducer",$r->reduce($m),"Merge[D Merge[^F A]]");
}

function test_nodes_nw() {
	assert_tree_equals("test_node_nw_chord",mm\Chord::nw(),"Chord<notes:[C]>");
	assert_tree_equals("test_node_nw_arp",mm\Arp::nw(),"Arp<orderPattern:[0],lengthInNotes:1,chord:Chord<notes:[C]>>");
	assert_tree_equals("test_node_nw_header",mm\Header::nw(),"Header<header:>[C]");
	assert_tree_equals("test_node_nw_key",mm\Key::nw(),"Key<key:C>[C]");
	assert_tree_equals("test_node_nw_measure",mm\Measure::nw(),"Measure[C]");
	assert_tree_equals("test_node_nw_merge",mm\Merge::nw(),"Merge[C C]");
	assert_tree_equals("test_node_nw_multiplex",mm\Multiplex::nw(),"Multiplex<channels:2>[C]");
	assert_equals("test_node_nw_note",mm\Note::nw()->toStringCompact(),"C");
	assert_tree_equals("test_node_nw_parallel",mm\Parallel::nw(),"Parallel[C C]");
	assert_tree_equals("test_node_nw_rep",mm\Rep::nw(),"Rep<reps:2>[C]");
	assert_tree_equals("test_node_nw_tempo",mm\Tempo::nw(),"Tempo<beatNote:1,beatsByMinute:60>[C]");
	assert_tree_equals("test_node_nw_then",mm\Then::nw(),"Then[C C]");
	assert_tree_equals("test_node_nw_time",mm\Time::nw(),"Time<quantity:4,duration:4>[C]");
}

function test_chainreducer() {
	assert_todo("volver a programar test chain reducer perdido");
	assert_todo("testear duraciones distintas de x/4");
	assert_todo("testear que todos los constructores nw funcionen sin parametros (defaults razonables)");
	assert_todo("verificar que todos los tipos de nodos tengan testeo(ej. arp/chord/rep");
	assert_todo("verificar que todos los tipos de nodos tengan testeo de sus reducciones especificas (ej arp/chord/rep)");
}

function test_multiplexreducer() {
	test_multiplexreducer_1();
	test_multiplexreducer_2();
	test_multiplexreducer_3();
}

function test_reducers() {
	test_multiplexreducer();
	test_chainreducer();
	test_measurereducer_1();
	test_measurereducer_2();
	test_measurereducer_3();
	test_chordreducer();
}

function test_thentolist() {
	$m=mm\notes("ABCD");	
	assert_equals("test_thentolist",mm\then_to_list($m),"A,B,C,D");	
}

function test_listtothen() {
	$notes=mm\then_to_list(mm\notes("ABCD"));	
	assert_tree_equals("test_listtothen",mm\list_to_then($notes),"Then[A Then[B Then[C D]]]");	
}

function test_testthennotecount() {
	assert_equals("test_testthennotecount",mm\then_note_count(mm\notes("ABCD")),4);
}

function test_utils() {
	test_thentolist();
	test_listtothen();
	test_testthennotecount();
}
// testeos pendientes: clazz,nw,toStringCompact,customs-unary,customs-binary
//tag/withTag vs. constructor - definir bien esto.
//abstract para method clazz.verificar que esté definido en todos (no está)

function test() {
//	test_nodes();
	test_nodes_nw();
//	test_utils();
//	test_reducers();
}

test();

?>
