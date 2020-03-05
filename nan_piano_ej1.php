<?php
namespace nan\mm;

use nan\mm;
use nan\mm\abc;
use nan\mm\measure;
use nan\mm\transpose;
use nan\mm\arp;

require_once("autoloader.php");

new mm\MmNs();
new abc\ABCNs();

/* ejemplos */
 function ej1_c1() {
 	return notes("C");
 } 

 function ej1_c2() {
 	return notes("CEGB");
 } 
 
 function ej1_cs8() {
 	return notes("CDEFGABAB2B2C2C2");
 }

 function ej11() {
 	return ej1_cs8(); // merge(ej1_cs8()new rep(merge(ej1_cs8(),new rep(ej1_c1(),4)),1);
 }

 function ej12() {
 	return merge(ej1_cs8(),new rep(ej1_c2(),4));
 }


function ej() {
	//return new header(new tempo(new key(new time(new merge([new up8th(ej11()),ej11(),new down8th(ej11())]),3,4),"Fmaj"),array("composer"=>"Nan","title"=>"piano exercises"),1/4,300));	

	$m=
		header::nw(["composer"=>"Nan","title"=>"piano exercises"])
		->addNode(key::nw("Fmaj"))
		->addNode(time::nw(3,4))
		->addNode(tempo::nw(1/4,200))
		->addNode(merge::nw()
//			->addNode(ej11())
			->addNode(ej11()->wrap(up8th::nw()))
			->addNode(ej11())
			->addNode(ej11()->wrap(down8th::nw()))
			->wrap(rep::nw(2))
		);

	return $m;
}


function main() {
	mm\debug("main");
 	$m=ej();
 	$abcStr=(new abc\AbcReducer())->reduce($m);
 	//$m_measured_tree=$m_measured->toStringTree();
 	//$reducer=new transpose\TransposeReducer();
 	//$reducer2=new merge\MergeReducer();
 	//$m_reduced=$reducer->reduce($m_measured);
 	//$m_reduced2=$reducer2->reduce($m_reduced);
 	//$m_reduced_tree=$m_reduced->toStringTree();
 	//print sprintf("source-melody-measured:$m_measured\n");
 	//print sprintf("source-melody-measured-tree:$m_measured_tree\n");
 	//print sprintf("source-melody-reduced:$m_reduced\n");
 	//print sprintf("source-melody-reduced-tree:$m_reduced_tree\n");
 	//print "source-melody-measured:".$mo->toStringTree()."\n";
 	print "target-abc:\n";
 	print "--------\n";
 	print "$abcStr\n";
 	print "--------\n";
 	abc\abc_store($abcStr,"ej.abc");
 	abc\abc2midi("ej.abc");
 }

print main();

?>