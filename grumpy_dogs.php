<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Grumpy Dogs Demo - Robert Sims</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="./css/grumpy_style.css" rel="stylesheet">

		<script>
			
			var objDogs = '';

			$.ajax({
			    url: "https://data.austintexas.gov/resource/h8x4-nvyi.json",
			    type: "GET",
			    data: {
			      "$limit" : 15,
			    }
			}).done(function(data) {

				var i = 0;

				$.each(data, function(index, doggy_bag) {

					var dogDesc = doggy_bag.description_of_dog;

					// there are no bad dogs... just bad strings...
					// replace smart quotes and weird commas
					dogDesc = dogDesc.replace('“', '"');
					dogDesc = dogDesc.replace('”', '"');
					dogDesc = dogDesc.replace('",', '"');
					dogDesc = dogDesc.replace(',"', '"');

					// extract name from description
					dogName = dogDesc.split(/"/)[1];
					dogDesc = dogDesc.split(/"/)[2].trim();

					// ensure first character of description is uppercase
					dogDesc = dogDesc.charAt(0).toUpperCase() + dogDesc.substr(1);

					// id="dog_list"
					$("#dog-list ul").append('<li class="dog-item" id="' + i + '" name="' + dogName + '" data="' + dogDesc.replace('"', "&quot;") + '" onclick="updateDisplay($(this))">' + dogName + '</li>');

					i++;

				});


			});

			function updateDisplay(that) {
				// console.log(that.attr('name'));
				// console.log(that.attr('data'));
				// console.log('------------------------');

				var doggyName = that.attr('name');
				var doggyData = that.attr('data');

				var doggyLower = doggyData.toLowerCase();

				var img;


				if ( doggyLower.indexOf("boxer") >= 0 ) {
					img = 'boxer.jpg';
				} else if ( doggyLower.indexOf("pit") >= 0 || doggyLower.indexOf("terrier") >= 0 ) {
					img = 'pit.jpg';
				} else if ( doggyLower.indexOf("bull") >= 0 ) {
					img = 'bulldog.jpg';
				} else if ( doggyLower.indexOf("collie") >= 0 ) {
					img = 'collie.jpg';
				} else if ( doggyLower.indexOf("shepherd") >= 0 || doggyLower.indexOf("cattle") >= 0 || doggyLower.indexOf("german") >= 0 ) {
					img = 'cattle-sheep.jpg';
				} else if ( doggyLower.indexOf("husky") >= 0 ) {
					img = 'husky.jpg';
				} else if ( doggyLower.indexOf("labrador") >= 0 || doggyLower.indexOf("retriever") >= 0 ) {
					img = 'labrador.jpg';
				} else {
					img = 'other.jpg';
				}

				var newStr;

				newStr  = '<h3>' + doggyName + '</h3>';
				newStr += '<p>' + doggyData + '<p>';
				newStr += '<img src="img/' + img + '" />';

				$('.doghouse').html(newStr);
			}
		  
		</script>

	</head>
	<body>
		<div class="container fill">
			<div class="page-header">
				Grumpy Dogs Demo - Robert Sims
			</div>

			<div class="row fill">
				<div class="col-md-8 col-xs-6  fill">
					<div class="header-right">In the Dog House</div>
					<div class="doghouse">Select a sad pupp-o!</div>
				</div>

				<div class="col-md-4 col-xs-6 fill" id="dog-list">
					<div class="header-left">Sad dogs need wags</div>
					<ul class="dog-list"></ul>
				</div>

			</div>

		</div>
	
		

		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	</body>
</html>