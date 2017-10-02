<?php

use PHPUnit\Framework\Testcase;

class customerDbTest extends TestCase {


	public function testInvalidCustomer_1()
	{

		$failString = 'abc123';

		try {
			$customer = customerDb::fetchCustomer($failString);

		} catch (Exception $e) {

			$customer = array("ERROR" => $e->getMessage());

		}

        	$this->assertArrayHasKey("ERROR", $customer);
    	}
}
