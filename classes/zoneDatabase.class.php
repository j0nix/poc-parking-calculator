<?php
class zoneDatabase {

	protected $zone_data = null;	// Variable to store zone data from database

	/*
		The Zone database
		
		REGNR => WEEKDAY => debit_start_time, debit_stop_time, rate, rate_unit, max_debit, extended_rates => duration_for_rate, rate

	*/
        private $database = array(
                "1234" => array(
                        0 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        1 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        2 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        3 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        4 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        5 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        6 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                ),
                "1235" => array(
                        0 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        1 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        2 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        3 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        4 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        5 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                        6 => array("start" => "09:00","stop" => "18:00","rate" => 5, "unit" => 3600,"max_debit" => 25, "extended_rate" => array(array("duration" => 3600, "rate" => 10))),
                )
        );

	public function __construct($zoneId=null) {
		/* 
			If we include a zone reference we try to set variable zone_data 
		*/
		if(!is_null($zoneId)) if(!$this->setZone($zoneId)) throw new Exception(__CLASS__."::".__FUNCTION__."::Invalid zone ($zoneId)");
        }

        public function __destruct() {
                $this->zonedata = null;
	}

	/*
		Get data from database to and set zone_data
	*/
        public function setZone($id) {

		if(array_key_exists($id,$this->database)) { // Does this zone exist in the database ?
			$this->zone_data = $this->database[$id];
			return true;
		} else {
			return false;
		}
        }

	/*
		Just return zonedata, if not set we return null
	*/
	public function getZone() {
		return $this->zone_data;
	}
}
?>
