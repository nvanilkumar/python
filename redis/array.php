<?php
class arrayFunctions{

	public $dialedCustomers;
	
	function __construct($array) {
		$this->dialerCustomersCount=$array;
       
   }

   public function getValue(){
   		var_dump($this->dialerCustomersCount);
   }

	public function insertData($value)
	{
		$this->dialerCustomersCount[]=$value;

	}
	public function checkValue($searchValue)
	{
		return array_search($searchValue, $this->dialerCustomersCount);

	}
	public function deleteValue($deleteIndex){
		array_splice($this->dialerCustomersCount, $deleteIndex, 1);

	}

}

$input = array("red", "green", "blue", "yellow");
$values= new arrayFunctions($input);

$values->insertData("57@1000");
$values->getValue();


//checking the value

$status=$values->checkValue("green");
$values->getValue();
if($status != false){
	$values->deleteValue($status);
	$values->getValue();
}





