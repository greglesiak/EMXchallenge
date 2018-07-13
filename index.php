<?php
ini_set("log_errors", 1);
ini_set("error_log", "emx.log");
include 'testController.php';

$test = new Test_Controller;

//get the variables being passed to the service
$q = $_GET['q'];
$d = $_GET['d'];

//send $d, which happens to be a single unique word that we can look up to pull the answer for, passing $q for the initial ping
$response = $test->get_answer($q, $d);
if($q == 'Puzzle'){
	foreach($response as $r){
		echo($r);
		echo("\n");
		error_log($r);
	}
}else{
	echo $response;
	}



?>