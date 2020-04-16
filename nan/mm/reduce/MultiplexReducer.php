<?php
namespace nan\mm\reduce;
use nan\mm;

class MultiplexReducer extends NodeReducer {
	function reduceMultiplex($m,$c) {
			$firstNode=null;
			$last=$m->channels()-1;
			for($i=$last;$i>=0;$i--) {
				if ($i==$last) {
					$firstNode=$m->uniqueNode();
				} else {
					$firstNode=mm\merge::nw($m->uniqueNode(),$firstNode);
				}

			}

		return $this->reduce($firstNode);
	}
}

?>