<?php

function abc2midi($abcfile) {
	$abc2midi_runner="\"C:\\Program Files (x86)\\runabc\\abc2midi.exe\"";
	$abc2midi_cmd="$abc2midi_runner $abcfile -o $abcfile.midi";
	$out=array();
	$ret=-1;
	print "ejecutando: $abc2midi_cmd\n";
	$res=exec($abc2midi_cmd,$out,$ret);
	print_r($out);


}
abc2midi("sample.abc");

print "ok";

?>
