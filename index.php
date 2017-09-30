<?php
header('Content-Type: text/plain; charset=utf-8');
openlog('php', LOG_CONS | LOG_NDELAY | LOG_PID, LOG_USER | LOG_PERROR);
// Display errors
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// TimeZone for all these DateTime objects
date_default_timezone_set("GMT");

// Autoload classes
spl_autoload_register(function ($class){
	include_once('classes/' . $class . '.class.php');
});

/* 
 	Proof Of Concept Requirements
        -----------------------------------------
        | Alla dagar 09:00 - 18:00: 5 kr / tim  |
        | Första timmen: 10 kr / timme          |
        | Övrig tid: 0 kr / tim                 |
        | Max pris per dygn: 25 kr              |
	-----------------------------------------

*/

/* ----------------------------------------------------- */

	$START_TIME_FOR_PARKING = "2017-09-26 10:00";
	$STOP_TIME_FOR_PARKING = "2017-09-26 12:00";
	$CUSTOMER = "PJA188";
	$PARKED_IN_ZONE = "1234";

/* ----------------------------------------------------- */

/*
	Really want to use these variables instead ...
*/

$start_time	= (isset($_GET['startTime'])	? 	$_GET['startTime']	: $START_TIME_FOR_PARKING);
$stop_time	= (isset($_GET['stopTime'])	? 	$_GET['startTime']	: $STOP_TIME_FOR_PARKING);
$customer	= (isset($_GET['customer'])	? 	$_GET['customer']	: $CUSTOMER);
$zone		= (isset($_GET['zone'])		? 	$_GET['zone']		: $PARKED_IN_ZONE);

/*
	Try to create DateTime objects of your timestamps and set them as
	epoch timestamps instead
*/

try {
        $start_time = unixTime::human2unix($start_time);
	$stop_time = unixTime::human2unix($stop_time);

} catch (Exception $e) {
	syslog(LOG_ERR,"{$e->getMessage()}");
	//echo "{$e->getMessage()}\n";
        exit(1);
}

/*
	Incoming data for this POC
*/

$incoming_debit_data = array(

	array("customer" => $customer, "zone" => $zone, "start" => $start_time, "stop" => $stop_time ),
	/* Added some more example data */
	array("customer" => "KLM545", "zone" => "1235", "start" => unixTime::human2unix("2017-09-25 13:35"), "stop" => unixTime::human2unix("2017-09-25 13:45")),
	array("customer" => "HRE018", "zone" => "1235", "start" => unixTime::human2unix("2017-09-25 18:01"), "stop" => unixTime::human2unix("2017-09-27 22:25")),
	array("customer" => "KLM545", "zone" => "1233", "start" => unixTime::human2unix("2017-09-25 18:01"), "stop" => unixTime::human2unix("2017-09-27 22:25")),
	array("customer" => "KLM545", "zone" => "1235", "start" => unixTime::human2unix("2017-09-28 18:01"), "stop" => unixTime::human2unix("2017-09-27 22:25")),
	array("customer" => "KLM545", "zone" => "1235", "start" => unixTime::human2unix("2017-09-28 06:45"), "stop" => unixTime::human2unix("2017-09-28 10:25")),
	array("customer" => "KLM545", "zone" => "1235", "start" => unixTime::human2unix("2017-09-28 16:06"), "stop" => unixTime::human2unix("2017-09-30 13:45"))

);

/* 
 	 Where we going to store calculated invoice data
*/

$outgoing_debit_data = array();

/*
	Start processing incomming debit data
*/

foreach ($incoming_debit_data as $data) {

	try {
		/* Get customer data, just for fun */
		$customer = customerDb::fetchCustomer($data['customer']);

		/* Initiate zone and calculate debit data */
		$parking_zone = new zoneParking($data['zone'],$data['start'],$data['stop']);

	} catch (Exception $e) {
		syslog(LOG_ERR,sprintf("FALLOUT;%s|FALLOUT-DATA;%s\n",$e->getMessage(),join(",",$data)));
		continue;
        }

	/*
		Add data to outgoing debit. We store data per customer, where customer from incoming_debit_data is used as a key in array
		If we have more than one row to process we need tom make sure that this customer don't already exists in 
		outgoing data. If so, we add debit data otherwise create that "container" storing this customers debit data.
	*/
	
	// A lot of carrige-return here, just to visualize stuff
	if(array_key_exists($data['customer'],$outgoing_debit_data)) {

		array_push($outgoing_debit_data[$data['customer']]['billing'],array(

			"debits" => $parking_zone->getDebit(), /* <= Get debit data */

			"start" => unixTime::unix2human($data['start']),

			"stop" => unixTime::unix2human($data['stop']),

			"zone" => $data['zone']

			)
		);

	} else $outgoing_debit_data[$data['customer']] = array(

		"customer" => $customer,
		"billing" => array(
				array(
					"debits" => $parking_zone->getDebit(),

					"start" => unixTime::unix2human($data['start']),

					"stop" => unixTime::unix2human($data['stop']),

					"zone" => $data['zone']
				)
		)
	);
}
/*
	Some printing of debit data
*/

if(isset($_GET['json'])) { printer::json($outgoing_debit_data); } 
else { printer::text($outgoing_debit_data); }
closelog();

//j0nix2018
?>
