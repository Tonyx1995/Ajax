<!--
	Little script to place the pages outside of the table-responsive div at the bottom
 	The reason being: if we don't move it out, the table-responsive gives us a nasty
 	horizontal line across the screen.
-->
<script type="text/javascript">
	$("document").ready(function(){
		$('.pages').parent().after($('.pages').addClass("text-center col-centered"));
	});
</script>

<div class="table-responsive">
	<table class="table table-bordered table-hover foodTable">
		<tr>
			<th class="displaySpaceSmall">Preview</th>
			<th>Name</th>
			<th class="displaySpace">Description</th>
			<th>Price</th>
			<th class="displaySpace">Cyclone<br />Card Price</th>
			<?php
				if(isset($_SESSION['admin'])){
					echo "<th>Edit</th>";
					echo "<th>Delete</th>";
				}
			?>
		</tr>
		<?php
			require("lib/PHP/db.php");
			include("lib/PHP/functions.php");
			
		
			//If user has set how many they'd like to see per page through the session; assign it. If not, default to 10.
			if(isset($_SESSION['page_limit'])) $page_break_limit = $_SESSION['page_limit']; else $page_break_limit = 10;
			$offset = 0;
			$page = 0;
			$type_select = array();

			if(isset($_GET['search'])) $search = $_GET['search']; else $search = null;

			if(isset($_POST['type'])){
				//Checking an array because of multiple selections
				//Update this so that the SQL statement tells us WHERE to go
				foreach($_POST['type'] as $type){
					array_push($type_select, $type);
				}
			}

			if(isset($_GET['page'])){
				$page = $_GET['page'];
				$offset = $page * $page_break_limit;
			}



			if($search || count($type_select) > 0){
				//Displaying the searched for data.
				display_food_table_search($dbh, $search, $type_select);
			}else{
				//Displaying the data.
				display_food_table($dbh, $page, $page_break_limit, $offset);
			}
		?>
	</table>
</div>

<!-- Edit Food Modal -->
<div class="modal fade" id="editFoodModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Edit Item</h4>
			</div>
			<!-- Loading content from our ajax call to populate the modal-body -->
			<div class="modal-body edit-food-modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-toggle='modal' data-target='#uploadModal'>
					<span class="glyphicon glyphicon-upload"></span>&nbsp;Upload Image
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Image</h4>
			</div>
			<!-- Loading content from our ajax call to populate the modal-body -->
			<div class="modal-body upload-modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<!-- Delete Item Modal -->
<div class="modal fade" id="deleteModal" role="dialog">
	<div class="modal-dialog">
		<!-- Delete Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>
					<span class="glyphicon glyphicon-remove"></span>&nbsp;Delete Item
				</h4>
			</div>
			<div class="modal-body delete-modal-body text-center">
				<h3>Are you sure you want to delete this item?</h3>

				<!-- Container for item information & results -->
				<div class="item-information padded-heavy">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
	function display_food_table($dbh, $page, $page_break_limit, $offset){
		$query = "SELECT * FROM food_sale_food";

		if($page != 0){
			$query .= " LIMIT " . ($offset - $page_break_limit) . ", " . $page_break_limit;
		}

		$sql = $dbh->prepare($query);
		$sql->execute();

		$count = $sql->rowCount();

		foreach($sql as $row){
			//For calculating if today's date is within the sale range.
			$current_date = date("Y-m-d");
			$current_date = date("Y-m-d", strtotime($current_date));
			$sale_start = date("Y-m-d", strtotime($row['sale_start_date']));
			$sale_end = date("Y-m-d", strtotime($row['sale_end_date']));


			$dimArray = array();
			$dimArray = Functions::getThumbnail("lib/Styles/pics/food-", $row['pic']);

			echo "
					<tr id='id".$row['food_id']."'>";
						if($row['pic']){
							echo "<td class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/pics/food-".$row['pic']."' height='".$dimArray['height']."' width='".$dimArray['width']."'/></td>";
						}else{
							echo "<td class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/default.jpg' height='100px' width='100px' /></td>";
						}
			echo "	<td class='name'>".$row['food_name']."</td>
						<td class='displaySpace description'>".$row['food_description']."</td>";
						//If item is within sale range (inclusive)
						if(($current_date >= $sale_start) && ($current_date <= $sale_end) && $row['on_sale'] == 1){
							echo "<td style='font-weight: bold; color: red;'><img class='img-rounded' src='lib/Styles/sale.jpg' height='45px' width='45px' /><br /><s style='color: black; font-weight: normal;'>$".sprintf('%0.2f', $row['regular_price'])."</s><br />$".sprintf('%0.2f', $row['sale_price'])."</td>";
						}else{
							//If item was previously on sale, change flag in database to reflect new date
							if($row['on_sale'] == 1){
								$query = "UPDATE food_sale_food SET
												on_sale = 0
												WHERE food_id IN ('".$row['food_id']."')";

								$sql = $dbh->prepare($query);
								$sql->execute();
								header("LOCATION: index.php?page=1");
							}
							echo"<td>$".sprintf('%0.2f', $row['regular_price'])."</td>";
						}
			echo "	<td class='displaySpace cyclonePrice'>$".sprintf("%.02f", $row['cyclone_card_price'])."</td>";
						//If user is logged in as an admin, display an edit button and pass the data-id as the food-id.
						//This will tie up with the ajax modal loading for editing of food items.
						if(isset($_SESSION['admin'])) {
							echo "
							<td>
								<button id='edit_food_button' class='btn btn-default btn-block' data-toggle='modal' data-target='#editFoodModal' data-id='".$row['food_id']."'>
									<span class='glyphicon glyphicon-cog'></span>
								</button>
							</td>
							<td>
								<button id='delete_food_button' class='btn btn-danger btn-block' data-toggle='modal' data-target='#deleteModal' data-id='".$row['food_id']."'>
									<span class='glyphicon glyphicon-remove'></span>
								</button>
							</td>";
						}
			echo	"</tr>
				";
		}

		//Display Pages (if set)
		if(isset($_GET['page'])){

			//Formatting pagination
			echo"<div class='pages'><ul class='pagination'>";

			if($page > 1){
				echo"<li class='displaySpaceTiny'><a href='index.php?page=1'>First</a></li>";
			}

			$query = "SELECT COUNT(*) AS 'all_results' FROM food_sale_food";
			$sql = $dbh->prepare($query);
			$sql->execute();

			foreach($sql as $row){
				//Dividing total number of results by our page-break limit.
				$total_pages = ceil($row['all_results'] / $page_break_limit);

				//For printing out how many pages will be available to choose from at once.
				for($x = max($page - 2, 1); $x <= max(1, min($total_pages, $page + 2)); $x++){
					if($x == $page){
						echo"<li class='active'><a href='index.php?page=".$x."'>$x</a></li>";
					}else{
						echo "<li><a href='index.php?page=".$x."'>$x</a></li>";
					}
				}
			}
			if($page < $total_pages){
				echo"<li class='displaySpaceTiny'><a href='index.php?page=".$total_pages."'>Last</a></li>";
			}
			echo "</ul></div>";
		}

		if($count == 0){
			echo "<h3>No results. Try a different search.</h3>";
		}
	}

	function display_food_table_search($dbh, $search, $type_select){
		$search_conditions = array();

		$query = "SELECT * FROM food_sale_food";

		if($search){
			array_push($search_conditions, " food_name LIKE '%" . $search . "%'");
		}

		if(isset($_POST['type'])){
			$type_query = "SELECT food_id, type_id FROM food_sale_food_type_match WHERE type_id IN (";

			for($x = 0; $x < count($type_select); $x++){
				//If x is only one shy of the count of array, don't add the comma. It breaks SQL syntax
				if($x != (count($type_select) - 1)){
					$type_query .= "'" . $type_select[$x] . "', ";
				}else{
					$type_query .= "'" . $type_select[$x] . "'";
				}
			}

			$type_query .= ") ";

			$type_id_array = array();
			$food_id_type_array = array();

			$type_sql = $dbh->prepare($type_query);
			$type_sql->execute();
			foreach($type_sql as $row){
				//No duplicates
				if(!in_array($row['food_id'], $food_id_type_array)){
					array_push($food_id_type_array, $row['food_id']);
				}
			}
		}

		if(count($search_conditions) > 0){
			$query .= ' WHERE ' . implode(' AND ', $search_conditions);
		}
		if(isset($food_id_type_array) && count($food_id_type_array > 0)){
			//Search conditions above already set WHERE in clause, so we append to it with an AND
			if(count($search_conditions) > 0){
				$query .= ' AND food_id IN (';
				//Else, we start our own WHERE clause
			}else{
				$query .= ' WHERE food_id IN (';
			}

			for($x = 0; $x < count($food_id_type_array); $x++){
				if($x != (count($food_id_type_array) - 1)){
					$query .= "'" . $food_id_type_array[$x] . "', ";
				}else{
					$query .= "'" . $food_id_type_array[$x] . "')";
				}
			}
		}

		$query .= " ORDER BY on_sale DESC, food_name ASC";

		$sql = $dbh->prepare($query);
		$sql->execute();

		$count = $sql->rowCount();

		foreach($sql as $row){
			$dimArray = array();
			$dimArray = Functions::getThumbnail("lib/Styles/pics/food-", $row['pic']);

			echo "
						<tr id='id".$row['food_id']."'>";
			if($row['pic']){
				echo "<td class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/pics/food-".$row['pic']."' height='".$dimArray['height']."' width='".$dimArray['width']."'/></td>";
			}else{
				echo "<td class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/default.jpg' height='100px' width='100px' /></td>";
			}
			echo "	<td class='name'>".$row['food_name']."</td>
							<td class='displaySpace description'>".$row['food_description']."</td>
							<td class='price'>$".sprintf("%.02f", $row['regular_price'])."</td>
							<td class='displaySpace cyclonePrice'>$".sprintf("%.02f", $row['cyclone_card_price'])."</td>";
			//If user is logged in as an admin, display an edit button and pass the data-id as the food-id.
			//This will tie up with the ajax modal loading for editing of food items.
			if(isset($_SESSION['admin'])) {
				echo "
								<td>
									<button id='edit_food_button' class='btn btn-default btn-block' data-toggle='modal' data-target='#editFoodModal' data-id='".$row['food_id']."'>
										<span class='glyphicon glyphicon-cog'></span>
									</button>
								</td>
								<td>
									<button id='delete_food_button' class='btn btn-danger btn-block' data-toggle='modal' data-target='#deleteModal' data-id='".$row['food_id']."'>
										<span class='glyphicon glyphicon-remove'></span>
									</button>
								</td>";
			}
			echo	"</tr>
					";
		}

		if($count == 0){
			echo "<h3>No results. Try a different search.</h3>";
		}
	}
?>