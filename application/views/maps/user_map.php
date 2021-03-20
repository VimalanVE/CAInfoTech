<!DOCTYPE html>
<html>
  <head>
    <title>CA Infotech</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 50%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script>
      let map;
      function initMap() {
        const lat = <?php echo $user_location->latitude; ?>;
        const long = <?php echo $user_location->longitude; ?>;
        const address = "<?php echo $user_location->address; ?>";
        const myLatLng = { lat: lat, lng: long };
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 10,
          center: myLatLng,
        });
        var infowindow = new google.maps.InfoWindow();
        var marker;
        marker = new google.maps.Marker({
          position: myLatLng,
          map,
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            infowindow.setContent(address);
            infowindow.open(map, marker);
          }
        })(marker));
      }
    </script>
  </head>
  <body>
  <div class="container">
  <a type="button" href="<?php echo base_url(); ?>index.php/user/logout" 
  class="btn btn-danger pull-right"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
  <h1 class="page-header text-center">CA Infotech</h1>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		<div class="login-panel panel panel-primary">
			<?php
				$user = $this->session->userdata('user');
				extract($user);
				$location = json_decode($location_coordinates);
			?>
			<div class="panel-heading">
		            <h3 class="panel-title text-center">
                  <span class="glyphicon glyphicon-user"></span> Logged In User Details</h3>
		    </div>
			<div class="panel-body">
				<p>Name: <?php echo $name; ?></p>
				<p>Email: <?php echo $email; ?></p>
				<p>Phone: <?php echo $phone; ?></p>
				<p>Address: <?php echo $location->address; ?></p>
			</div>
		</div>
		</div>
  </div>
  <div class="row">
      <div class="col-md-12">
        <h4 class="text-center">Logged-In User Location (Using Latitude and Longitude) :</h4>
        <div id="map" style="width:100%; height:400px;"></div>
      </div>
    </div>
</div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=&sensor=false&callback=initMap&libraries=&v=weekly"
      async
    ></script>
  </body>
</html>