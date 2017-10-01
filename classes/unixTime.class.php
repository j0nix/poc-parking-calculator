<?php
/* 
	Helper functions for transform Date Time objects
 
 */

class unixTime extends DateTime {

    	private function getEpoch($dtm) {
		if($dtm instanceOf DateTime) {
			return intval($dtm->format('U'));
		} else throw new UnexpectedValueException(sprintf("%s::%s::Invalid DateTime %s",__CLASS__,__FUNCTION__,$dtm));
    	}

    	private function getHuman($epoch) {
        	if($epoch instanceOf DateTime) {
        	        return $epoch->format('Y-m-d H:i:s');
        	} else throw new UnexpectedValueException(sprintf("%s::%s::Invalid DateTime %s",__CLASS__,__FUNCTION__,$epoch));
    	}


	/* 
		Transform a timestamp to epoch/unixtime 
	*/
    	public static function human2unix($timestamp) { $dtm = new self($timestamp); return $dtm->getEpoch($dtm); } 

	/*
		Transform a unix/epoch timestamp to ISO8601:ish 
	*/
	public static function unix2human($timestamp) { $dtm = new self("@$timestamp"); return $dtm->getHuman($dtm); } 
}
?>
