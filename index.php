<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Import for Bootstrap, ajax, jQuery -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript" src="//code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

		<script src="lib/Scripts/JSFunctions.js"></script>
		<link rel="stylesheet" href="lib/Styles/Styles.css" />
		<title>Ajax Test</title>
	</head>
	<?php
		require("lib/PHP/db.php");
		session_start();

		//testing entire post array
		//print_r($_POST);

		//Getting data from letting user pick their own page limits.
		if(isset($_POST['page_limit'])) $_SESSION['page_limit'] = $_POST['page_limit'];

		if(isset($_SESSION['username'])) $logged_in = true; else $logged_in = false;
		if(isset($_SESSION['admin'])) $is_admin = true; else $is_admin = false;

	?>

	<body class="container">
		<!-- Navbar -->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Ajax & Modal Boostrap:</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li><a data-toggle="modal" data-target="#loginModal" href="#">Sample Link</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a id="registerButton" data-toggle="modal" data-target="#registerModal" href="#">
								<span class="glyphicon glyphicon-user"></span> Sign Up
							</a>
						</li>
						<li><a id="loginButton" data-toggle="modal" data-target="#loginModal" href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
						<li><a id="logoutButton" data-toggle="modal" data-target="#logoutModal" href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row padded-medium">
					<div class="col-sm-offset-3 col-sm-6">
						<h3 class='text-center'>
							Inventory
							<?php
								if($is_admin) {
									echo '
									<a class="btn btn-success pull-right" data-toggle="modal" data-target="#addFoodModal" href="#" >
										<span class="glyphicon glyphicon-plus" ></span >
									</a >';
								}
							?>
						</h3>
						<form role="form" class="form-horizontal">
							<div class='input-group'>
								<input class='form-control' type='text' name='search' id='search' placeholder='Food Name..' />
								<span class='input-group-btn'>
									<button class='btn btn-success' type='submit'>
										<span class="glyphicon glyphicon-search"></span>&nbsp;Search
									</button>
								</span>
							</div>
						</form>
						<div class="additional-options">
							<a href='index.php'>
								<span class='glyphicon glyphicon-circle-arrow-left'></span>&nbsp;Reset
							</a>
							<div class="pull-right display-pages">
								<a href="index.php?page=1">
									Show Pages&nbsp;<span class="glyphicon glyphicon-list-alt"></span>
								</a> |
								<a href="#" data-toggle='modal' data-target='#optionsModal'>
									Options&nbsp;<span class="glyphicon glyphicon-cog"></span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<!-- Display Table -->
				<?php include("indexDisplay.php"); ?>
			</div>
		</div>

		<!-- Register Modal -->
		<div class="modal fade" id="registerModal" role="dialog">
			<div class="modal-dialog">
				<!-- Register Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">
							<span class="glyphicon glyphicon-pencil"></span>&nbsp;Register
						</h4>
					</div>
					<form id="registerForm" role="form" class="form-horizontal">
						<div class="modal-body register-modal-body padded-form">
							<!--Form will be posted to ajax script-->
							<div class="form-group">
								<label for="username"><span class="glyphicon glyphicon-user"></span>&nbsp;Username</label>
								<input class="form-control" type="text" name="username" id="regUsername" placeholder="Username.." />
							</div>
							<div class="form-group">
								<label for="password"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;Password</label>
								<input class="form-control" type="password" name="password" id="regPassword" placeholder="Password.." />
							</div>
							<div class="form-group">
								<label for="confirmPassword">Confirm Password</label>
								<input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password.." />
							</div>
							<div class="passwordAlert alert alert-danger fade in" style='display: none;'>
								<span class="glyphicon glyphicon-ban-circle"></span>&nbsp
								Make sure the passwords entered match and you've supplied a username.
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary" class="btn btn-default">Register</button>
							<button type="button" class="btn btn-default pull-left clear-form-button">Reset</button>
						</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Login Modal -->
		<div class="modal fade" id="loginModal" role="dialog">
			<div class="modal-dialog">
				<!-- Login Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">
							<span class="glyphicon glyphicon-log-in"></span>&nbsp;Login
						</h4>
					</div>
					<form id="loginForm" role="form" class="form-horizontal">
						<div class="modal-body login-modal-body padded-form">
							<!--Form will be posted to ajax script-->
							<div class="form-group">
								<label for="username"><span class="glyphicon glyphicon-user"></span>&nbsp;Username</label>
								<input class="form-control" type="text" name="username" id="username" placeholder="Username.." />
							</div>
							<div class="form-group">
								<label for="password"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;Password</label>
								<input class="form-control" type="password" name="password" id="password" placeholder="Password.." />
							</div>
							<div class="passwordAlert alert alert-danger fade in" style='display: none;'>
								<span class="glyphicon glyphicon-ban-circle"></span>&nbsp
								Must fill out a value for the username and password fields.
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Login</button>
							<button type="button" class="btn btn-default pull-left clear-form-button">Reset</button>
						</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Logout Modal -->
		<div class="modal fade" id="logoutModal" role="dialog">
			<div class="modal-dialog">
				<!-- Login Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4>
							<span class="glyphicon glyphicon-log-out"></span>&nbsp;Logout
						</h4>
					</div>
					<div class="modal-body logout-modal-body text-center">
						<h3>Are you sure you want to logout?</h3>
					</div>
					<form role='form' class='form-horizontal' method='POST' id='logoutForm'>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Logout</button>
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Add Item Modal -->
		<div class="modal fade" id="addFoodModal" role="dialog">
			<div class="modal-dialog">
				<!-- Login Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">
							Add Item
						</h4>
					</div>
					<div class="modal-body add-food-modal-body padded-form text-center">
						<!--Form will be posted to ajax script-->
						<form role='form' class='form-horizontal' method='POST' id='add_food_form'>
							<div class='addFoodFormDiv col-centered padded-form'>
								<div class='form-group'>
									<label for='food_name'>Food Name</label><span class='red'>&#42;</span>
									<input class='form-control food-name' type='text' name='food_name' id='food_id' placeholder='Food Name..' />
								</div>
								<div class='form-group'>
									<label for='food_description'>Description</label><span class='red'>&#42;</span>
									<textarea style='resize: none;' class='form-control food-desc' maxlength='250' rows='4' id='food_description' name='food_description' placeholder='Description..'></textarea>
									<h5><em><span class='remainingCount'>(250 character limit)</span></em></h5>
								</div>
								<div class='form-group'>
									<label for='regular_price'>Price</label><span class='red'>&#42;</span>
									<div class='input-group'>
										<span class='input-group-addon'>$</span>
										<input class='form-control regular-price' type='text' name='regular_price' id='regular_price' placeholder='Price..' />
									</div>
								</div>
								<div class='form-group'>
									<h4>Cyclone Card Item?</h4>
									<label class='radio-inline showCyclone'><input type='radio' name='cyclone_card' value='Yes' id='showCyclone'>Yes</label>
									<label class='radio-inline hideCyclone'><input type='radio' name='cyclone_card' value='No' id='hideCyclone' checked>No</label>
									<div class='displayCyclonePrice padded-heavy' style='display: none;'>
										<div class='form-group'>
											<label for='cyclone_card_price'>Cyclone card price</label>
											<div class='input-group'>
												<span class='input-group-addon'>$</span>
												<input type='text' class='form-control cyclone-card-price' name='cyclone_card_price' id='cyclone_card_price' placeholder='Cyclone card price..' />
											</div>
										</div>
									</div>
								</div>
								<div class='form-group'>
									<h4>Sale Item?</h4>
									<label class='radio-inline showSales'><input type='radio' name='sales' value='on' id='showSales'>Yes</label>
									<label class='radio-inline hideSales'><input type='radio' name='sales' value='off' id='hideSales' checked>No</label>
									<div class='displaySales padded-heavy' style='display:none;'>
										<div class='form-group'>
											<label for='a-sale_start_date'>Sale Start Date</label>
											<input class='form-control datepicker sale-start-date' type='text' id='a-sale_start_date' name='sale_start_date' placeholder='Click to set date range..' />
										</div>
										<div class='form-group'>
											<label for='a-sale_end_date'>Sale End Date</label>
											<input class='form-control datepicker sale-end-date' type='text' id='a-sale_end_date' name='sale_end_date' placeholder='Click to set date range..' />
										</div>
										<div class='form-group'>
											<label for='sale_price'>Sale Price</label>
											<div class='input-group'>
												<span class='input-group-addon'>$</span>
												<input class='form-control sale-price' type='text' name='sale_price' id='sale_price' placeholder='Enter a sale price..' />
											</div>
										</div>
									</div>
								</div>
								<div class='displayAlert padded-heavy' style='display: none;'>
									<div class='alert alert-danger fade in'>
										<span class='glyphicon glyphicon-ban-circle'></span>&nbsp;
										Fill out all required fields.
									</div>
								</div>
								<div class='displayPriceAlert padded-heavy' style='display: none;'>
									<div class='alert alert-danger fade in'>
										<span class='glyphicon glyphicon-ban-circle'></span>&nbsp;
										The sale/cyclone card price <em>cannot be higher than or equal to</em> the regular price.<br />
										Also, make sure the prices given are valid.
									</div>
								</div>
								<div class='displayNumericAlert padded-heavy' style='display: none;'>
									<div class='alert alert-danger fade in'>
										<span class='glyphicon glyphicon-ban-circle'></span>&nbsp;
										All prices entered must be valid numbers.
									</div>
								</div>
								<div class='displayDateAlert padded-heavy' style='display: none;'>
									<div class='alert alert-danger fade in'>
										<span class='glyphicon glyphicon-ban-circle'></span>&nbsp;
										Must fill out the start date, the end date, and the sale price.
									</div>
								</div>
								<div class='typeAlert' style='display: none;'>
									<div class='alert alert-danger fade in'>
										<span class='glyphicon glyphicon-ban-circle'></span>&nbsp;
										Must have atleast one type set for this item.
									</div>
								</div>
								<div class="form-group">
									<?php
										$checkresult = Functions::fetch_all_types($dbh);

										foreach($checkresult as $typeRow){
											echo"<label class='checkbox-inline'><input type='checkbox' name='type[]' value='".$typeRow['type_id']."'>".$typeRow['type_name']."</label>";
										}
									?>
								</div>
								<button type='submit' class='btn btn-primary text-center submitAddFood'>Add</button>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Options & Filter Modal -->
		<div class="modal fade" id="optionsModal" role="dialog">
			<div class="modal-dialog">
				<!-- Login Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4>
							Search & Filter Options
						</h4>
					</div>
					<form role="form" id="optionsForm" method="POST" class="form-horizontal">
						<div class="modal-body options-modal-body padded-form">
							<div class="form-group">
								<h4># of Items per page</h4>
								<div class="radio">
									<label><input type="radio" name="page_limit" value="5">5</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="page_limit" value="10">10</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="page_limit" value="25">25</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="page_limit" value="50">50</label>
								</div>
							</div>

							<!-- Dynamically load all types via ajax call to php script here. -->
							<div class="form-group all-types">

							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" name="filter-button" value="search" class="btn btn-primary">Apply Filter(s)</button>
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</body>
</html>

<?php
	if($logged_in){
		//Calling jQuery to hide the register/login buttons if user is logged in.
		echo"
		<script type=text/javascript>
			$('#loginButton').hide();
			$('#registerButton').hide();
		</script>
		";
		//Hide logout button if they aren't logged in.
	}else{
		echo"
		<script type=text/javascript>
			$('#logoutButton').hide();
		</script>
		";
	}
?>