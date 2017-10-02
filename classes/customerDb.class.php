<?php
final class customerDb
{
        private $customerDb = array(
                "PJA188" => "56234452, J0nix Rulez, Awsomeroad 265B, 12323 Ljungskile",
                "KLM545" => "92433666, John Rambo, Machinegun road M16, 12445 Knivsta"
	);

        private function __construct($id) {
                $this->validCustomer($id);
	}

        public static function fetchCustomer($id) {
		$customer = new self($id);
		if(!array_key_exists($id,$customer->customerDb)) throw new InvalidArgumentException(sprintf(__CLASS__."::".__FUNCTION__."::%s was not found in customer database",$id));
		else return $customer->customerDb[$id];
        }

        private function validCustomer($id) {
		if(!preg_match('/^[A-Z]{3}[0-9]{3}$/',$id)) throw new UnexpectedValueException(sprintf(__CLASS__."::".__FUNCTION__."::%s is not a valid customer id",$id));
        }
}
?>
