<?php

// **************************************************
//
// - List the available routes. 
//   You can fetch this data by querying `routes.txt`.
//
// - List the trips for a specified route. 
//   You can fetch this data by querying `trips.txt`.
//
// - List the stops for a specified trip. 
//   You can fetch this data by querying `trips.txt` and `shapes.txt`.
//
// **************************************************

class TransitTools {

	private $dataPath;
	

	function __construct ($dataPath = './data/gtfs-capmetro-june-2018/') {
		
		if ( $dataPath !== '') {
			$this->dataPath = $dataPath;

		} else {
			// TODO:  Some sort of error handing for no path argument
			// $rtn = json_encode( array("error", "No path to data was supplied.") );
		}
	}


	function getRoutes() {
		// get routes from dataPath/routes.txt and return as json
		$csvFile = $this->dataPath . 'routes.txt';
		$rtn = $this->jsonWizard( $csvFile );

		return $rtn;
	}


	function getTrips ( $routeID = 0 ) {
		// get trips from dataPath/trips.txt and return as json
		$csvFile = $this->dataPath . 'trips.txt';
		$rtn = $this->jsonKeyWizard( $csvFile, 'route_id', $routeID );

		return $rtn;
	}


	function getStops ( $routeID = 0, $direction = -1 ) {
		// build stops list and return as json

		// default message assumes something didn't work as planned.
		$rtn = json_encode( array('message' => 'An unknown error has occurred') );

		if ( $routeID == 0 || $direction < 0 ) {
			// developer needs to supply a route_id and direction value
			$rtn = json_encode( array('message' => 'route_id or direction not specified') );
		} else {
			// get stops for given route from dataPath/trips.txt and then combine dataPath/shapes.txt
			$csvFile = $this->dataPath . 'trips.txt';
			$tripData = json_decode($this->jsonKeyWizard( $csvFile, 'route_id', $routeID ), TRUE);
			$aryData = array();

			// map the meta trip data for specific route
			$aryData['route_id'] 				= $tripData[0]['route_id'];
			$aryData['service_id'] 				= $tripData[0]['service_id'];
			$aryData['trip_headsign'] 			= $tripData[0]['trip_headsign'];
			$aryData['direction_id'] 			= $tripData[0]['direction_id'];
			$aryData['shape_id'] 				= $tripData[0]['shape_id'];
			$aryData['wheelchair_accessible'] 	= $tripData[0]['wheelchair_accessible'];
			$aryData['bikes_allowed'] 			= $tripData[0]['bikes_allowed'];
			$aryData['dir_abbr'] 				= $tripData[0]['dir_abbr'];

			// Get shape data and merge with parent trips data
			$shapeData = json_decode( $this->getShapes($tripData[0]['shape_id']), TRUE );

			// add shape_count meta value in case it's ever useful
			$aryData['shape_count'] = count($shapeData);

			// set up shape data parent node
			$aryData['shape_data']	= array();
			
			for ($i=0; $i<count($shapeData); $i++) {
				$aryData['shape_data'][$i]['shape_pt_sequence']	= $shapeData[$i]['shape_pt_sequence'];
				$aryData['shape_data'][$i]['shape_pt_lat'] 		= $shapeData[$i]['shape_pt_lat'];
				$aryData['shape_data'][$i]['shape_pt_lon'] 		= $shapeData[$i]['shape_pt_lon'];
			}

			// define return json 
			$rtn = json_encode($aryData);
		}

		return $rtn;
	}


	function getShapes ( $shapeID = 0 ) {
		// get shapes from dataPath/shapes.txt and return as json
		$csvFile = $this->dataPath . 'shapes.txt';
		$rtn = $this->jsonKeyWizard( $csvFile, 'shape_id', $shapeID );

		return $rtn;
	}


	function getRoutesNearLocation ($lat, $lng) {}


	private function jsonWizard( $pathToCSV ) {
		// csv to json conversion tool

		// attempt to open CSV
		if ( ($fh = fopen($pathToCSV, 'r')) !== FALSE ) {
			// get key labels for CSV header 
			$keys = fgetcsv($fh, 999, ',');
			$aryData = array();

			// process data and combine with labels
			while ($line = fgetcsv($fh, 999, ',') ) {
				$aryData[] = array_combine( $keys, $line );
			}

			$rtn = json_encode($aryData);

			fclose($fh);

		} else {
			// TODO: Error handling
			$rtn = json_encode( array('message' => 'Failed to open data file: ' . $this->dataPath) );
		}

		return $rtn;
	}

	private function jsonKeyWizard( $pathToCSV, $key = '', $value = 0 ) {
		// csv to json conversion tool based on supplied key=>value

		// attempt to open CSV
		if ( ($fh = fopen($pathToCSV, 'r')) !== FALSE ) {

			// get key labels for CSV header 
			$keys = fgetcsv($fh, 999, ',');
			$aryData = array();

			// process data while applying key/value filter
			while ($line = fgetcsv($fh, 999, ',') ) {
				$dataNode = array_combine( $keys, $line );

				// if key/value filter is met, combine data with labels
				if ( $dataNode[$key] == $value ) {
					$aryData[] = $dataNode;
				}
			}

			// define return json
			$rtn = json_encode($aryData);
			fclose($fh);

		} else {
			// simple error message
			$rtn = json_encode( array('message' => 'Failed to open data file: ' . $this->dataPath) );
		}

		return $rtn;
	}


}



?>