<?php

use PHPUnit\Framework\Testcase;

class customerDbTest extends TestCase {


	public function testInvalidCustomer_1()
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
}
