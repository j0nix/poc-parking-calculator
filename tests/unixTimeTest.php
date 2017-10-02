<?php

date_default_timezone_set("GMT");

use PHPUnit\Framework\Testcase;

class unixTimeTest extends TestCase {

	private $human = '2017-10-01 00:00:00';
	private $epoch = 1506816000;

	public function testHuman2Unix()
	{

		$fail = false;
		$msg = null;

		try {
			$epoch = unixTime::human2unix($this->human);

			if(intval($epoch) != $this->epoch) {
				$fail = true;
				$msg = "$epoch";
			}

		} catch (Exception $e) {
			$fail = true;
			$msg = $e->getMessage();
		}

        	$this->assertFalse($fail,$msg);
	}

	public function testUnix2Human()
	{

		$fail = false;
		$msg = null;

		try {
			$human = unixTime::unix2human($this->epoch);

			if($human != $this->human) {
				$fail = true;
				$msg = $human;
			}

		} catch (Exception $e) {
			$fail = true;
			$msg = $e->getMessage();
		}

        	$this->assertFalse($fail,$msg);
    	}
}
