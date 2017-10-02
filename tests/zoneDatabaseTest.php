<?php

use PHPUnit\Framework\Testcase;

class zoneDatabaseTest extends TestCase {


	public function testInvalidZone()
	{

		$zone = '666';
		$fail = false;
		$msg = null;

		try {
			$zoneObj = new zoneDatabase($zone);

		} catch (Exception $e) {
			$fail = true;
			$msg = "{$e->getMessage()}";
		}

        	$this->assertTrue($fail,$msg);
	}

	public function testValidZone()
	{

		$zone = '1235';
		$fail = false;
		$msg = null;

		try {
			$zoneObj = new zoneDatabase($zone);

		} catch (Exception $e) {
			$fail = true;
			$msg = "{$e->getMessage()}";
		}

        	$this->assertFalse($fail,$msg);
    	}
}
