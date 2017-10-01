<?php

class zoneParking extends zoneDatabase implements ParkingZones {

        private $zone_debit = array();	//Store calculated zone data

	public function __construct($zoneid=null,$start=null,$stop=null) {
		/*
			get that zone_data we require to calculate debits
		*/
		parent::__construct($zoneid);

		if(!is_null($this->zone_data) && !is_null($start) && !is_null($stop)) { // If we initiated this class included all required varibles to calculate, lets calculate!
			
			if(!$this->setDebit($start,$stop)) throw new Exception(__CLASS__."::".__FUNCTION__."::Error setting debit data");
		} 

        }
	/*
		Get delta between 2 unix timestamps, only returns positive numbers
	*/
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

	/*
		Calculate debits and push calculated data to zone_debit
	*/
        private function setZoneDebit($data) {

		if(!is_array($data)) {

			throw new Exception(__CLASS__."::".__FUNCTION__."::Invalid/empty input data, not an array?");
			return false;

		} else {

			foreach ($data as $debit ) {

				if(array_key_exists('extended_rate',$debit['debit_data'])) {	// Do we have some extended rates in this zone?

					if(count($debit['debit_data']['extended_rate'] > 0)) {
                                	        $this->calcExtendedRate($debit);
                                	}
				}

                                if($debit['debit_time'] > 0) {
                                        $debit['debit'] += $debit['debit_time'] * ($debit['debit_data']['rate'] / $debit['debit_data']['unit']);
                                }

				if($debit['debit'] > $debit['debit_data']['max_debit']) {	// If cost exceeds maximun debit in zone, we set debit to max

					$debit['debit'] = intval($debit['debit_data']['max_debit']);
                                }

                                array_push($this->zone_debit,array("start" => $debit['debit_start'],"stop" => $debit['debit_stop'],"debit" => $debit['debit']));
                        }
                }
			
                return true;
        }
	/*
		Helper function to setZoneDebit, when we have extended rates like double the reate first hour etc.
		References that data we send to this function, hence we change values in the referenced variable.
	*/
        private function calcExtendedRate(&$extendedRate) {

		$cost = 0;

                foreach($extendedRate['debit_data']['extended_rate'] as $rate) {

			/*
				if time to debit is smaler then time we have extended rate, we calculate with extended rate 
				else we calculate cost for all time we have extended rate and substract that time from time to debit. 
			*/
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

	/*
		Set variable zone_debits, that is the array storing calculated debits for the delta time between included timestamps.  
	*/
        public function setDebit($parkingStart,$parkingStop) {

                if (!is_null($this->zone_data)) {	//If we don't have any zone data we don't have to bother try to calculate anything

			$debits = array();

			/*
				making a DateTime object of unix timestamps
			*/
                        $parking_start = new DateTime("@$parkingStart");
			$parking_stop = new DateTime("@$parkingStop");
			
			$BEGIN = new DateTime($parking_start->format('Y-m-d'));	// This is that day we start our parking
			$END = new DateTime($parking_stop->format('Y-m-d'));	// This is the day we stoped out parking ( can be the same day as start )

			/*
				Makes no sence that we stoped paking before we start parking
			 */
			if($BEGIN > $END) throw new Exception(__CLASS__."::".__FUNCTION__."::Start time is greater then stop time. Please check input data");

			/*
				We need to identify times we start to debit for parking. 
				So from start to stop parking we create debit timestamps, 
				comparing our input time whith time this zone debit parking
			*/	
			while ($END >= $BEGIN) {	// loop for as long as we have been parking 

				/*
					Create timestamps representing this zones start and stop time for debit
				*/
				$zone_start = new DateTime($BEGIN->format('Y-m-d')." ".$this->zone_data[$BEGIN->format('w')]['start']);
				$zone_stop = new DateTime($BEGIN->format('Y-m-d')." ".$this->zone_data[$BEGIN->format('w')]['stop']);

				/*
					If we startded parking after this zones stop time for debit, we can skip to next day (if any)
				*/
				if(intval($parking_start->format('U')) < intval($zone_stop->format('U'))) {

					/*
						If we parked after this zone starts its debit we start to debit from when we parked, otherwise the clock starts ticking from when this zone starts its debit
					 */

					$debit_start = (intval($parking_start->format('U')) > intval($zone_start->format('U')) ? intval($parking_start->format('U')) : intval($zone_start->format('U')));

					/*
						If we stoped parking before this zone stops its debits we set stop to that time, otherwise we stop the clock when this zone stops its debit
					 */

                                	$debit_stop = (intval($parking_stop->format('U')) < intval($zone_stop->format('U')) ? intval($parking_stop->format('U')) : intval($zone_stop->format('U')));

					/* 
						Get the delta between these timestamps, we use that later to calculate what we should debit 
					 */

                                	$delta = $this->getDelta($debit_start,$debit_stop);

					/* 
						Just a container to store start and stop data that we need for calculate what our parking costs
					 */

					array_push($debits,array("debit" => 0, "debit_start" => $debit_start, "debit_stop" => $debit_stop, "debit_data" => $this->zone_data[$parking_start->format('w')],"debit_time" => $delta));
				}

				// Hop to the next day
				$BEGIN->modify('+1 day');
                        }

			/*
				Do the calculation of cost for our debits, aka what did it cost to park in this zone.
			*/
			return $this->setZoneDebit($debits);
		} 
		
		return false;
        }

        public function getDebit() {
                return $this->zone_debit;
        }
}

?>
