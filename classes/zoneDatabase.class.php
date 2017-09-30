<?php
class zoneDatabase {

	protected $zone_data = null;
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
		if(!is_null($zoneId)) if(!$this->setZone($zoneId)) throw new Exception(__CLASS__."::".__FUNCTION__."::Invalid zone ($zoneId)");
        }

        public function __destruct() {
                $this->zonedata = null;
        }

        public function setZone($id) {

		if(array_key_exists($id,$this->database)) {
			$this->zone_data = $this->database[$id];
			return true;
		} else {
			return false;
		}
        }

	public function getZone() {
		return $this->zone_data;
	}
}
?>
