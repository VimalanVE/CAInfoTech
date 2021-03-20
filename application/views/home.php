<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CA Infotech</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container">
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
		            <h3 class="panel-title text-center"><span class="glyphicon glyphicon-user"></span> Logged In User Details</h3>
		    </div>
			<div class="panel-body">
				<p>Name: <?php echo $name; ?></p>
				<p>Email: <?php echo $email; ?></p>
				<p>Phone: <?php echo $phone; ?></p>
				<p>Address: <?php echo $location->address; ?></p>
				<a type="button" href="<?php echo base_url(); ?>index.php/user/logout" class="btn btn-lg btn-danger btn-block">
				<span class="glyphicon glyphicon-log-out"></span> Logout</a>
			</div>
		</div>
		</div>
	</div>
</div>
</body>
</html>
