<?php

spl_autoload_register(function ($class_name) {
	include 'incs/' . $class_name . '.php';
});

echo '<pre>';



$objTransit = new transitTools();

// print_r( $objTransit->getRoutes() );
// print_r( $objTransit->getStops(466) );
print_r( $objTransit->getTrips(3) );
// print_r( $objTransit->getStops(3, 0) );



?>