<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


spl_autoload_register(function ($class_name) {
	include 'incs/' . $class_name . '.php';
});

$objTransit = new transitTools();

$rtn = json_encode( array("message" => "An unknown error has occured.") );

// $msg = '';
// $err = FALSE;

// if ($err) {
// 	$rtn = json_encode( array("message" => $msg) );
// } else {
	$rtn = $objTransit->getRoutes($route, $direction);
// }



print_r( $rtn );

?>