<?php
	require("lib/PHP/db.php");
	$id = $_POST['food_id'];
	$food_name = $_POST['food_name'];
	$food_description = $_POST['food_description'];
	if(isset($_POST['cyclone_card'])) $cyclone_card = $_POST['cyclone_card']; else $cyclone_card = "No";
	if(isset($_POST['cyclone_card_price'])) $cyclone_card_price = $_POST['cyclone_card_price']; else $cyclone_card_price = 0;
	$regular_price = $_POST['regular_price'];


	if(ucfirst($cyclone_card) == "Yes" && $cyclone_card_price > 0){
		$cyclone_card = 1;
	}else{
		$cyclone_card = 0;
		$cyclone_card_price = 0;
	}

	//Getting the date-range picker values and exploding on a hyphen.
	//We get a value that comes in like 4/12/2016 - 4/26/2016. So that's why we're using explode.
	$sale_range = $_POST['sale_range'];
	$ranges = explode("-", $sale_range);
	$sale_start_date = $ranges[0];
	$sale_end_date = $ranges[1];

	$current_date = date("Y-m-d");
	$current_date = date("Y-m-d", strtotime($current_date));
	$sale_start_date = date("Y-m-d", strtotime($sale_start_date));
	$sale_end_date = date("Y-m-d", strtotime($sale_end_date));


	$sales = $_POST['sales'];
	$sale_price = $_POST['sale_price'];
	$sale_price = $_POST['sale_price'];


	$query = "UPDATE food_sale_food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					cyclone_card_item = '".$cyclone_card."',
					cyclone_card_price = '".$cyclone_card_price."',
					sale_price = '".$sale_price."',
					sale_start_date = '".format_date_update($sale_start_date)."',
					sale_end_date = '".format_date_update($sale_end_date)."',";
					if(($current_date >= $sale_start_date) && ($current_date <= $sale_end_date) && $sale_price > 0){
						$query .= " on_sale = '1' ";
					}else{
						$query .=" on_sale = '0' ";
					}

	$query.= "WHERE food_id = '".$id."'";
	$sql = $dbh->prepare($query);
	$sql->execute();

	echo "<h2>Item updated.</h2>";
	echo "<h2>".$query."</h2>";

function format_date($date){
	$formatted_date = '';
	$formatted_date = date('m/d/Y', strtotime($date));
	if($date == "1970-01-01"){
		return "";
	}else{
		return $formatted_date;
	}
}

function format_date_update($date){
	$formatted_date = '';
	$formatted_date = date('Y-m-d', strtotime($date));
	return $formatted_date;
}
?>