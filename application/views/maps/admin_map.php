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
   <script type="text/javascript">
   <?php
    if(!empty($location)) {?>
      var locations = <?php print_r($location); ?>;
      function initialize() {
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(locations[0]['address']['latitude'], locations[0]['address']['longitude']),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var infowindow = new google.maps.InfoWindow();

      var marker, i;
      
      for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i]['address']['latitude'], locations[i]['address']['longitude']),
          map: map
        });
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            infowindow.setContent(locations[i]['name'].concat(', ',locations[i]['address']['address']));
            infowindow.open(map, marker);
          }
        })(marker, i));
      }
    }
    <?php
    }else{
   ?>
   function initialize() {
   var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(12.9191,80.2300),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var infowindow = new google.maps.InfoWindow();

  }
      <?php
    }
   ?>
  </script>
  </head>
  <body>
  <div class="row">
    <?php
      $user = $this->session->userdata('user');
      extract($user);
      $location = json_decode($location_coordinates);
      $getQueryParams = $_GET;
    ?>
    <div class="col-md-8 text-center">
      <h4 class="text-center">Logged-In Admin Location : ( <?= $location->address ?> )</h4>
    </div>
    <div class="col-md-1">
      <a type="button" href="<?php echo base_url(); ?>index.php/user/logout" 
        class="btn btn-danger pull-right"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
    </div>
  </div>
    
	<div class="container">

    <div class="row">
      <div class="col-md-12">
        <div id="map" style="width:100%; height:400px;"></div>
      </div>
    </div>

  <div class="row">
    <div class="col-md-12">
      <h2>List Of Registered Users</h2>
    </div>
    <div class="col-md-12 form-group">
    <form action="<?php echo base_url(); ?>index.php/user/home" >
      <?php
        $checkKilometerFilterType = null;
        $checkMileFilterType = null;
        $getRadius = null;
        if(isset($getQueryParams['filter_type']) && $getQueryParams['filter_type'] == "kilometers") {
          $checkKilometerFilterType = "checked";
        }elseif(isset($getQueryParams['filter_type']) && $getQueryParams['filter_type'] == "miles"){
          $checkMileFilterType = "checked";
        }else{
          $checkKilometerFilterType = "checked";
        }
        if(isset($getQueryParams['radius'])) {
          $getRadius = $getQueryParams['radius'];
        }
      ?>
      <div class="row col-md-12">
        <div class="col-md-5"> 
          <label for="radius"> Enter Manual From Latitude  :</label>
          <input type="text" required name="manual_latitude" value=<?= $location->latitude ?>><br>
          <label for="radius"> Enter Manual From Longitude :</label>
          <input type="text" required name="manual_longitude" value=<?= $location->longitude ?>>
        </div>
        <div class="col-md-4"> 
          <label for="filter_type">Choose Filter type :</label>
          <input type="radio" name="filter_type" value="kilometers" <?= $checkKilometerFilterType ?>> Kilometers
          <input type="radio" name="filter_type" value="miles" <?= $checkMileFilterType ?>> Miles
          <br>
          <label for="radius"> Enter Filter Radius :</label>
          <input type="number" name="radius" required value=<?= $getRadius ?>>
        </div>
        <div class="col-md-3">
          <input class="btn btn-primary" type="submit" value="Submit">
          <a type="button" href="<?php echo base_url(); ?>index.php/user/home" 
          class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Clear Filter</a>
        </div>
      </div>
    </form>
    </div>
  </div>
        <ul class="list-group">
        <li class="list-group-item active">User Name<span class="badge">Current Location</span></li>
          <?php
          if(count($get_all_users)>0) {
          foreach($get_all_users as $user) { 
            $getLocation = json_decode($user['location_coordinates']);?>
            <li class="list-group-item"><?= $user['name'] ?><span class="badge"><?= $getLocation->address ?></span></li>
          <?php }}else{ ?>
            <li class="list-group-item">No user found</li>
            <?php }?>
        </ul>
  </div>
</div>
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=&sensor=false&callback=initialize&libraries=&v=weekly"
      async
    ></script>
  </body>
</html>