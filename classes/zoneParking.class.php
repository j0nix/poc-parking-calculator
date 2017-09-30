<?php

class zoneParking extends zoneDatabase implements ParkingZones {

        private $zone_debit = array();

	public function __construct($zoneid=null,$start=null,$stop=null) {

                parent::__construct($zoneid);
		if(!is_null($this->zone_data) && !is_null($start) && !is_null($stop)) {
			
			if(!$this->setDebit($start,$stop)) throw new Exception(__CLASS__."::".__FUNCTION__."::Error setting debit data");
		} 

        }

        private function getDelta($startTime,$stopTime) {

		$diff = null;

                // Diff in seconds
                $diff = intval($stopTime) - intval($startTime);

		if ($diff < 0) {
			$diff = null;
         		throw new Exception(__CLASS__."::".__FUNCTION__."::Invalid delta value ($diff), check your date inputs");
                }

                return $diff;
        }

        private function setZoneDebit($data) {

		if(!is_array($data)) {

			throw new Exception(__CLASS__."::".__FUNCTION__."::Invalid/empty input data, not an array?");
			return false;

		} else {

			foreach ($data as $debit ) {

				if(array_key_exists('extended_rate',$debit['debit_data'])) {

					if(count($debit['debit_data']['extended_rate'] > 0)) {
                                	        $this->calcExtendedRate($debit);
                                	}
				}

                                if($debit['debit_time'] > 0) {
                                        $debit['debit'] += $debit['debit_time'] * ($debit['debit_data']['rate'] / $debit['debit_data']['unit']);
                                }

				if($debit['debit'] > $debit['debit_data']['max_debit']) {

					$debit['debit'] = intval($debit['debit_data']['max_debit']);
                                }

                                array_push($this->zone_debit,array("start" => $debit['debit_start'],"stop" => $debit['debit_stop'],"debit" => $debit['debit']));
                        }
                }
			
                return true;
        }

        private function calcExtendedRate(&$extendedRate) {

		$cost = 0;

                foreach($extendedRate['debit_data']['extended_rate'] as $rate) {

                        if($extendedRate['debit_time'] <= $rate['duration']) {
                                $cost += $extendedRate['debit_time'] * ($rate['rate'] / $extendedRate['debit_data']['unit']);
                                $extendedRate['debit_time'] = 0;
                        } else {
                                $cost += $rate['duration'] * ($rate['rate'] / $extendedRate['debit_data']['unit']);
                                $extendedRate['debit_time'] -= $rate['duration'];
                        }

                        $extendedRate['debit'] += $cost;
                }
        }

        public function setDebit($parkingStart,$parkingStop) {

                if (!is_null($this->zone_data)) {

			$debits = array();

                        $parking_start = new DateTime("@$parkingStart");
			$parking_stop = new DateTime("@$parkingStop");
			
			$BEGIN = new DateTime($parking_start->format('Y-m-d'));
			$END = new DateTime($parking_stop->format('Y-m-d'));

			if($BEGIN > $END) throw new Exception(__CLASS__."::".__FUNCTION__."::Start time is greater then stop time. Please check input data");

			while ($END >= $BEGIN) {

				$zone_start = new DateTime($BEGIN->format('Y-m-d')." ".$this->zone_data[$BEGIN->format('w')]['start']);
				$zone_stop = new DateTime($BEGIN->format('Y-m-d')." ".$this->zone_data[$BEGIN->format('w')]['stop']);

				if(intval($parking_start->format('U')) < intval($zone_stop->format('U'))) {

					$debit_start = (intval($parking_start->format('U')) > intval($zone_start->format('U')) ? intval($parking_start->format('U')) : intval($zone_start->format('U')));

                                	$debit_stop = (intval($parking_stop->format('U')) < intval($zone_stop->format('U')) ? intval($parking_stop->format('U')) : intval($zone_stop->format('U')));

                                	$delta = $this->getDelta($debit_start,$debit_stop);
					
					array_push($debits,array("debit" => 0, "debit_start" => $debit_start, "debit_stop" => $debit_stop, "debit_data" => $this->zone_data[$parking_start->format('w')],"debit_time" => $delta));
				}

				$BEGIN->modify('+1 day');
                        }

			return $this->setZoneDebit($debits);
		} 
		
		return false;
        }

        public function getDebit() {
                return $this->zone_debit;
        }
}

?>
