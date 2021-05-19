<?php
namespace nan\mm;
use nan\mm\TwelveTone;
use nan\mm\Note;

require_once("autoloader.php");

abstract class MelodyModifier {
	 abstract function modify($melody);
}


?>