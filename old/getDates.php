<?php
	require("lib/db.php");

	$id = $_GET['id'];
	$query = "SELECT sale_start_date, sale_end_date FROM food_sale_food WHERE food_id = '".$id."'";
	$sql = $dbh->prepare($query);
	$sql->execute();

	//Return our starting and ending dates to parse out in javascript.
	foreach($sql as $row){
		echo $row['sale_start_date'] . ";" . $row['sale_end_date'];
	}
?>