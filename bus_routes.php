<?php
	spl_autoload_register(function ($class_name) {
		include 'engine/incs/' . $class_name . '.php';
	});

	$objTransit = new transitTools('./engine/data/gtfs-capmetro-june-2018/');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Routes Demo - Robert Sims</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">

		<style>
			#map {
				height: 600px;
				width: 100%;
				display: inline-block;
				float: right;
			}

			

		</style>
	</head>
	<body onload="initMap( 1, 0)">
		<div class="container fill">
			<div class="page-header">
				Routes Demo - Robert Sims
			</div>

			<div class="row fill">

				<div class="col-md-4 fill" style="">
					<div class="list-header">First 20 Routes</div>
					<div class="list-block">
						<ul>
							<?php
								$aryRoutes = json_decode($objTransit->getRoutes(), TRUE);

								for ($i=0; $i < 20; $i++) {
									// $aryRoutes[0][route_id]
									echo '<li onclick="initMap(' . $aryRoutes[$i]['route_id'] . ', 0)">' . $aryRoutes[$i]['route_long_name'] . '</li>' . PHP_EOL;
								}

							?>
						</ul>
					</div>
				</div>

				<div class="col-md-8  fill">
					<div class="detail-box">Loading</div>
					<div id="map"></div>
				</div>

			</div>

		</div>
	
		<script>

			function initMap(routeID, direction) {

				showLoading(1);

				var latLng = {lat: 30.2672, lng: -97.7431};
				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 9,
					center: latLng,
					styles: [ { "elementType": "geometry", "stylers": [ { "color": "#ebe3cd" } ] }, { "elementType": "labels.text.fill", "stylers": [ { "color": "#523735" } ] }, { "elementType": "labels.text.stroke", "stylers": [ { "color": "#f5f1e6" } ] }, { "featureType": "administrative", "elementType": "geometry", "stylers": [ { "visibility": "off" } ] }, { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [ { "color": "#c9b2a6" } ] }, { "featureType": "administrative.land_parcel", "stylers": [ { "visibility": "off" } ] }, { "featureType": "administrative.land_parcel", "elementType": "geometry.stroke", "stylers": [ { "color": "#dcd2be" } ] }, { "featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [ { "color": "#ae9e90" } ] }, { "featureType": "administrative.neighborhood", "stylers": [ { "visibility": "off" } ] }, { "featureType": "landscape.natural", "elementType": "geometry", "stylers": [ { "color": "#dfd2ae" } ] }, { "featureType": "poi", "stylers": [ { "visibility": "off" } ] }, { "featureType": "poi", "elementType": "geometry", "stylers": [ { "color": "#dfd2ae" } ] }, { "featureType": "poi", "elementType": "labels.text", "stylers": [ { "visibility": "off" } ] }, { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [ { "color": "#93817c" } ] }, { "featureType": "poi.park", "elementType": "geometry.fill", "stylers": [ { "color": "#a5b076" } ] }, { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [ { "color": "#447530" } ] }, { "featureType": "road", "elementType": "geometry", "stylers": [ { "color": "#f5f1e6" } ] }, { "featureType": "road", "elementType": "labels", "stylers": [ { "visibility": "off" } ] }, { "featureType": "road", "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "featureType": "road.arterial", "elementType": "geometry", "stylers": [ { "color": "#fdfcf8" } ] }, { "featureType": "road.highway", "elementType": "geometry", "stylers": [ { "color": "#f8c967" } ] }, { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [ { "color": "#e9bc62" } ] }, { "featureType": "road.highway.controlled_access", "elementType": "geometry", "stylers": [ { "color": "#e98d58" } ] }, { "featureType": "road.highway.controlled_access", "elementType": "geometry.stroke", "stylers": [ { "color": "#db8555" } ] }, { "featureType": "road.local", "elementType": "labels.text.fill", "stylers": [ { "color": "#806b63" } ] }, { "featureType": "transit", "stylers": [ { "visibility": "off" } ] }, { "featureType": "transit.line", "elementType": "geometry", "stylers": [ { "color": "#dfd2ae" } ] }, { "featureType": "transit.line", "elementType": "labels.text.fill", "stylers": [ { "color": "#8f7d77" } ] }, { "featureType": "transit.line", "elementType": "labels.text.stroke", "stylers": [ { "color": "#ebe3cd" } ] }, { "featureType": "transit.station", "elementType": "geometry", "stylers": [ { "color": "#dfd2ae" } ] }, { "featureType": "water", "elementType": "geometry.fill", "stylers": [ { "color": "#b9d3c2" } ] }, { "featureType": "water", "elementType": "labels.text", "stylers": [ { "visibility": "off" } ] }, { "featureType": "water", "elementType": "labels.text.fill", "stylers": [ { "color": "#92998d" } ] } ]
					// mapTypeId: google.maps.MapTypeId.TERRAIN
				});

				drawMarkers(map, routeID, direction);

			}

			function showLoading(boolShow) {

				// TODO: replace with animated loading script

				if (boolShow == 1) {
					$('.detail-box').html( 'Loading' );
				} else {
					$('.detail-box').html( '' );
				}
			}


			function drawMarkers(map, routeID, direction) {
				var bounds = new google.maps.LatLngBounds();

				$.ajax({
					url: 'engine/stops.php?route=' + routeID + '&direction=' + direction,
					dataType: 'json',

					success: function(json) {

						if ( json.hasOwnProperty('message') ) {
							console.log(json.message);
						}

						

						window.routes = json.shape_data;
						$.each(routes, function(index, routes) {

							var lat = routes.shape_pt_lat;
							var lng = routes.shape_pt_lon;

							// console.log(lat + ' ' + lng);

							if ( lat != '' && lng != '' ) {

								var latLng = new google.maps.LatLng(lat,lng);
								var marker = new google.maps.Marker({
									position: latLng,
									icon: {
											path: google.maps.SymbolPath.CIRCLE,
											strokeColor: '#0000ff',
											scale: 1
									},
									map: map
								});

								bounds.extend(marker.position);

							}

							map.fitBounds(bounds);
							showLoading(0);
							$('.detail-box').html( 'Headsign: ' + json.trip_headsign );
						});
					},
					error: function(){
						// some fancy error handling fallback should go here.
						// but for now I'll just let myself know there was an error.
						console.log(' ---- error');
					}
				});
			}

		

		  
		</script>
		<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCW0LSht7ijgsOKZEbwuFkoPHCfLQoh81Q&callback=initMap"></script> -->
	 	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCW0LSht7ijgsOKZEbwuFkoPHCfLQoh81Q"></script>
	
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	</body>
</html>