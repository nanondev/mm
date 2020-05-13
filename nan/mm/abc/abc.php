<?php
namespace nan\mm\abc;
use nan\mm;
use nan\mm\reduce;

class AbcNs {}

function abc2midi($abcfile) {
	$abc2midi_runner="\"C:\\Program Files (x86)\\runabc\\abc2midi.exe\"";
	$abc2midi_cmd="$abc2midi_runner $abcfile -o $abcfile.midi";
	$out=array();
	$ret=-1;
	mm\debug("abc2midi cmd: $abc2midi_cmd");
	$res=exec($abc2midi_cmd,$out,$ret);
	//print_r($out);
}

function abc_store($abcstr,$abcfile) {
	file_put_contents($abcfile,$abcstr);
}

?>
