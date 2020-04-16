<?php
namespace nan\mm;

class Header extends UnaryNode {
	var $header;
	function __construct($header,$unaryNode) {
		parent::__construct($unaryNode);
		$this->header=$header;
	}

	static function nw($header,$unaryNode) {
		return new Header($header,$unaryNode);
	}

	function header() {
		return $this->header;
	}

	function toStringCompact() {
		return sprintf("<%s>:\n%s\n",join(', ',$this->header),($this->toStringNodes()));
	}

	function toStringAttributes() {
		$s="";
		foreach($this->header as $k=>$v) {
			if (strlen($s)>0) $s.=" ";
			$s.="$k='$v'";		
		}
		return sprintf("header:%s",$s);
	}
	
	static function clazz() {
		return get_class(Header::nw([],Note::nw("C")));
	}

	function mapToString() {

	}
}

?>