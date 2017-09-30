<?php
// Template for unified zone functions 
interface ParkingZones {
	public function setDebit($parkingStart,$parkingStop);
        public function getDebit();
        public function setZone($id);
	public function getZone();
}
?>
