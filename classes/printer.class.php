<?php

//Printer "tool"
class printer {

	private $data = array();	// Stores data we like to print

	private function __construct($print=null) {
		/*
			Just make sure we included arraysdata to print
		*/
		if(is_array($print)) {
                	$this->data = $print;
                }
	}

	/*
		separator/header when printing data as text
	*/
	private function separator($regnr,$customer) {
		$tmp = '';for($x=0;$x<100;$x++) { $tmp .="-"; }
		print ("\n\n   $tmp\n   $regnr $customer\n   $tmp");
	}

	/*
		Print your data formated as defined in function
	*/
	public static function text($debitData) {

		$printer = new self($debitData);

		foreach ($printer->data as $regnr => $customer) {
			
			$printer->separator($regnr,$customer['customer']);

			foreach ($customer['billing'] as $billing_row) {

				printf("\n\t[ZONE %s] Parked between %s and %s \n",$billing_row['zone'],$billing_row['start'], $billing_row['stop']);
				foreach($billing_row['debits'] as $debit_row) {
					printf("\n\t\t* %s - %s , debit: %.2f :-\n",unixTime::unix2human($debit_row['start']),unixTime::unix2human($debit_row['stop']),$debit_row['debit']); 
				}
			}
		}
	}

	/*
		print data as prettyprint json
	*/
	public static function json($debitData) {

		header('Content-Type: application/json; charset=utf-8');
                $printer = new self($debitData);
        	echo json_encode($printer->data,JSON_PRETTY_PRINT);
	}
}
?>
