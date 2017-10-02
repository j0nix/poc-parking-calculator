<?php

use PHPUnit\Framework\Testcase;

class customerDbTest extends TestCase {


	public function testInvalidCustomer()
	{

		$failString = 'abc123';
		$fail = false;

		try {
			$customer = customerDb::fetchCustomer($failString);
		} catch (Exception $e) {
			$fail = true;
		}

        	$this->assertTrue($fail);
	}

	public function testValidCustomer()
	{

		$failString = 'PJA188';

		$fail = false;

		try {
			$customer = customerDb::fetchCustomer($failString);

		} catch (Exception $e) {
			$fail = true;
		}

        	$this->assertFalse($fail);
    	}
}
