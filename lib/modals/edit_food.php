<?php
	require("../PHP/db.php");
	include("../PHP/functions.php");


	if(isset($_POST['food_id'])){
		$query = "SELECT * FROM food_sale_food WHERE food_id = " . $_POST['food_id'];
		$sql = $dbh->prepare($query);
		$sql->execute();
		$row = $sql->fetch(PDO::FETCH_ASSOC);

		//Loading dimensions for thumbnail.
		$dimArray = array();
		$dimArray = Functions::getThumbnail("../Styles/pics/food-", $row['pic']);

		//Fetching existing types for this item.
		$typeArray = array();
		$typeArray = Functions::fetch_existing_types($_POST['food_id'], $dbh);

		if($row['pic']){
			echo "
			<div class='form-group' id='edit-food-image".$row['food_id']."'>
				<img class='img-rounded' src='lib/Styles/pics/food-".$row['pic']."' height='".$dimArray['height']."' width='".$dimArray['width']."'/>
			</div>
			";
		}else{
			echo "
			<div class='form-group'>
				<img class='img-rounded' src='lib/Styles/default.jpg' height='100px' width='100px' />
			</div>	
			";
		}

		echo"
			<form role='form' class='form-horizontal' method='POST' id='edit_food_form'>

				<div class='col-centered padded-form'>
					<!--To pass along the id -->
					<input type='hidden' name='food_id' value='".$_POST['food_id']."' />
					<div class='form-group'>
						<label for='food_name'>Food Name</label><span class='red'>&#42;</span>
						<input class='form-control food-name' type='text' name='food_name' id='food_id' placeholder='Food Name..' value='".$row['food_name']."' />
					</div>
					<div class='form-group'>
						<label for='food_description'>Description</label><span class='red'>&#42;</span>
						<textarea style='resize: none;' class='form-control food-desc' maxlength='250' rows='4' id='food_description' name='food_description' placeholder='Description..'>"
							.$row['food_description'].
						"</textarea>
						<h5><em><span class='remainingCount'>(250 character limit)</span></em></h5>
					</div>

					<div class='form-group'>
						<label for='regular_price'>Price</label><span class='red'>&#42;</span>
						<div class='input-group'>
							<span class='input-group-addon'>$</span>
							<input class='form-control regular-price' type='text' name='regular_price' id='regular_price' placeholder='Price..' value='".sprintf("%.02f", $row['regular_price'])."' />
						</div>
					</div>
					<div class='form-group'>
						<h4>Cyclone Card Item?</h4>";
						isCycloneItem($row);
		echo"   	</div>
					<div class='form-group'>
						<h4>Sale Item?</h4>";
						isSaleItem($row);
		echo"		</div>
					<div class='form-group'>
						<h4>Item Type(s)</h4>";
						displayTypes($row, $dbh);
		echo"		</div>
					<div class='displayAlert padded-heavy' style='display: none;'>
						<div class='alert alert-danger fade in'>
							<span class='glyphicon glyphicon-ban-circle'></span>&nbsp
							Fill out all required fields.
						</div>
					</div>
					<div class='displayPriceAlert padded-heavy' style='display: none;'>
						<div class='alert alert-danger fade in'>
							<span class='glyphicon glyphicon-ban-circle'></span>&nbsp
							The sale/cyclone card price <em>cannot be higher than or equal to</em> the regular price.<br />
							Also, make sure the prices given are valid.
						</div>
					</div>
					<div class='displayNumericAlert padded-heavy' style='display: none;'>
						<div class='alert alert-danger fade in'>
							<span class='glyphicon glyphicon-ban-circle'></span>&nbsp
							All prices entered must be valid numbers.
						</div>
					</div>
					<div class='displayDateAlert padded-heavy' style='display: none;'>
						<div class='alert alert-danger fade in'>
							<span class='glyphicon glyphicon-ban-circle'></span>&nbsp
							Must fill out the start date, the end date, and the sale price.
						</div>
					</div>
					<div class='typeAlert' style='display: none;'>
						<div class='alert alert-danger fade in'>
							<span class='glyphicon glyphicon-ban-circle'></span>&nbsp
							Must have atleast one type set for this item.
						</div>
					</div>
					<button type='submit' class='btn btn-primary text-center submitEditFood'>Update</button>
				</div>
		</form>
		";
	}else{
		echo "<h3>An error occured.</h3>";
	}

	function isCycloneItem($row){
		//Displaying whether or not the cyclone fields show up
		if(isset($row['cyclone_card_price']) && $row['cyclone_card_price'] > 0){
			echo"
				<label class='radio-inline showCyclone'><input type='radio' name='cyclone_card' value='Yes' id='showCyclone' checked>Yes</label>
				<label class='radio-inline hideCyclone'><input type='radio' name='cyclone_card' value='No' id='hideCyclone'>No</label>
				
			<div class='displayCyclonePrice padded-heavy'>
				<div class='form-group'>
					<label for='cyclone_card_price'>Cyclone card price</label>
					<div class='input-group'>
						<span class='input-group-addon'>$</span>
						<input type='text' class='form-control cyclone-card-price' name='cyclone_card_price' id='cyclone_card_price' placeholder='Cyclone card price..' value='".sprintf("%.02f", $row['cyclone_card_price'])."' />
					</div>
				</div>
			</div>
			";
		}else{
			echo"
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
			";
		}
	}

	function isSaleItem($row){
		if(isset($row['on_sale']) && $row['on_sale'] == 1){
			echo"
				<label class='radio-inline showSales'><input type='radio' name='sales' value='on' id='showSales' checked>Yes</label>
				<label class='radio-inline hideSales'><input type='radio' name='sales' value='off' id='hideSales'>No</label>
				
				<div class='displaySales padded-heavy'>
					<div class='form-group'>
						<label for='sale_start_date'>Sale Start Date</label>
						<input class='form-control datepicker sale-start-date' type='text' id='sale_start_date' name='sale_start_date' placeholder='Click to set date range..' value='".Functions::format_date($row['sale_start_date'])."' />
					</div>	
					<div class='form-group'>
						<label for='sale_end_date'>Sale End Date</label>
						<input class='form-control datepicker sale-end-date' type='text' id='sale_end_date' name='sale_end_date' placeholder='Click to set date range..' value='".Functions::format_date($row['sale_end_date']) . "' />
					</div>
					<div class='form-group'>
						<label for='sale_price'>Sale Price</label>
						<div class='input-group'>
							<span class='input-group-addon'>$</span>
							<input class='form-control sale-price' type='text' name='sale_price' id='sale_price' placeholder='Enter a sale price..' value='".sprintf('%0.2f', $row['sale_price'])."' />
						</div>
					</div>
				</div>
			";
		}else{
			echo"
				<label class='radio-inline showSales'><input type='radio' name='sales' value='on' id='showSales'>Yes</label>
				<label class='radio-inline hideSales'><input type='radio' name='sales' value='off' id='hideSales' checked>No</label>
				
				<div class='displaySales padded-heavy' style='display:none;'>
					<div class='form-group'>
						<label for='sale_start_date'>Sale Start Date</label>
						<input class='form-control datepicker sale-start-date' type='text' id='sale_start_date' name='sale_start_date' placeholder='Click to set date range..' />
					</div>
					<div class='form-group'>
						<label for='sale_end_date'>Sale End Date</label>
						<input class='form-control datepicker sale-end-date' type='text' id='sale_end_date' name='sale_end_date' placeholder='Click to set date range..' />
					</div>
					<div class='form-group'>
						<label for='sale_price'>Sale Price</label>
						<div class='input-group'>
							<span class='input-group-addon'>$</span>
							<input class='form-control sale-price' type='text' name='sale_price' id='sale_price' placeholder='Enter a sale price..' />
						</div>
					</div>
				</div>
			";
		}
	}

	function displayTypes($row, $dbh){
		$existing_types = Functions::fetch_existing_types($row['food_id'], $dbh);

		$counter = 0;

		$checkresult = Functions::fetch_all_types($dbh);

		foreach($checkresult as $typeRow){
			//If it's in the database, pre-check the checkbox
			if(count($existing_types) > 0){
				if(isset($existing_types[$counter]) && in_array($typeRow['type_id'], $existing_types)){
					echo"<label class='checkbox-inline'><input type='checkbox' name='type[]' checked value='".$typeRow['type_id']."'>".$typeRow['type_name']."</label>";
					//Only increment when a match is found.
					$counter++;
				}else{
					echo"<label class='checkbox-inline'><input type='checkbox' name='type[]' value='".$typeRow['type_id']."'>".$typeRow['type_name']."</label>";
				}
			}else{
				echo"<label class='checkbox-inline'><input type='checkbox' name='type[]' value='".$typeRow['type_id']."'>".$typeRow['type_name']."</label>";
			}
		}
	}
?>
