<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CA Infotech Registration<</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
<div class="container">
	<h1 class="page-header text-center">CA Infotech Registration</h1>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="login-panel panel panel-primary">
		        <div class="panel-heading">
		            <h3 class="panel-title text-center"><span class="glyphicon glyphicon-lock"></span> Registration</h3>
		        </div>
		    	<div class="panel-body">
		        	<form method="POST" action="<?php echo base_url(); ?>index.php/user/register_user">
		            	<fieldset>
		                	<div class="form-group">
		                    	<input class="form-control" placeholder="Name" type="text" name="name" required>
		                	</div>
							<div class="form-group">
		                    	<input class="form-control" placeholder="Email" type="email" name="email" required>
		                	</div>
		                	<div class="form-group">
		                    	<input class="form-control" placeholder="Password" type="password" name="password" required>
		                	</div>
		                	<div class="form-group">
		                    	<input class="form-control" placeholder="Phone No" type="text" name="phone_no" required>
		                	</div>
							<div class="form-group current_location">
								Use Current Location :
		                    	<input class="form-control" type="text" name="location" value="<?php echo $location->city .','.$location->country_name ?>" disabled>
		                	</div>
							<div class="form-group manual_location_div">
							<input type="checkbox" id="manual_location_checkbox" name="manual_location" onchange="valueChanged()">
								<label for="manual_location"> Manually Enter Location</label>
							</div>
							<div class="form-group enter_manual" style="display: none;">
								<label> Enter Location Name</label>
								<input class="form-control" type="text" name="address" value="<?= $location->city .','.$location->country_name ?>">
								<br>
								<label> Enter Latitude</label>
								<input class="form-control" type="text" name="latitude" value="<?= $location->latitude ?>">
								<br>
								<label> Enter Longitude</label>
								<input class="form-control" type="text" name="longitude" value="<?= $location->longitude ?>">
							</div>
		                	<button type="submit" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span> Register</button>
							<a type="button" href="<?php echo base_url(); ?>index.php/" class="btn btn-lg btn-warning btn-block"></span> Login</a>
		            	</fieldset>
		        	</form>
		    	</div>
		    </div>
			<?php
				if($this->session->flashdata('error')){
					?>
					<div class="alert alert-danger text-center" style="margin-top:20px;">
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php
				}elseif($this->session->flashdata('success')){?>
					<div class="alert alert-success text-center" style="margin-top:20px;">
						<?php echo $this->session->flashdata('success'); ?>
					</div>
			<?php
			}
			?>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
    function valueChanged()
    {
        if($('#manual_location_checkbox').is(":checked")) {
            $(".enter_manual").show();
			$(".current_location").hide();
		}else {
            $(".enter_manual").hide();
			$(".current_location").show();
		}
    }
</script>
</html>