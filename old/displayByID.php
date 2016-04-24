<?php
	require("lib/db.php");

	$id = $_GET['id'];

	$query = "
					SELECT food_id, food_name, food_description, regular_price, sale_price, on_sale, cyclone_card_price, pic FROM food_sale_food WHERE food_id = '".$id."';
				";
	$sql = $dbh->prepare($query);
	$sql->execute();

	foreach($sql as $row) {
		echo $query. "
				<td class='name'>" . $row['food_name'] . "</td>
				<td class='displaySpace description'>" . $row['food_description'] . "</td>
				<td class='price'>$" . sprintf("%.02f", $row['regular_price']) . "</td>
				<td class='displaySpaceSmall cyclonePrice'>$" . sprintf("%.02f", $row['cyclone_card_price']) . "</td>
				<td>
					<button id='edit_food_button' class='btn btn-default btn-block' data-toggle='modal' data-target='#editFoodModal' data-id='" . $row['food_id'] . "'>
						<span class='glyphicon glyphicon-cog'></span>
					</button>
				</td>
		";
	}
?>