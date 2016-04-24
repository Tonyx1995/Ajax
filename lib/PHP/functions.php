<?php
	class Functions{

		static public function format_date($date){
			$formatted_date = date('m/d/Y', strtotime($date));
			if($date == "1970-01-01"){
				return "";
			}else{
				return $formatted_date;
			}
		}

		static public function format_date_update($date){
			$formatted_date = date('Y-m-d', strtotime($date));
			return $formatted_date;
		}

		/* Returns an array with width and height stored in it */
		static public function getThumbnail($filepath, $image){
			//For thumbnails with the food image.
			$im = null;
			if (ISSET($image) && $image != ''){
				//Local
				$file =$filepath . $image;
				//Deployed
				//$file ="/srv/webapps.sctcc.edu/html/placement/html/upload/food-" . $image;
				$size = getimagesize($file);
				if ($size["mime"] == "image/jpeg"){
					$im = imagecreatefromjpeg($file); // jpeg file
				}else if ($size["mime"] =="image/gif"){
					$im = imagecreatefromgif($file); //gif file
				}else if ($size["mime"] =="image/png"){
					$im = imagecreatefrompng($file); //png file
				}
				$w = imagesx($im);
				$h = imagesy($im);
				$tw=$w;
				$th=$h;
				if($w > 120){
					$ratio = 120 / $w;
					$th = $h * $ratio;
					$tw = $w * $ratio;
				}elseif($h > 130){
					$ratio = 130 / $h; // get ratio for scaling image
					$th = $h * $ratio;
					$tw = $w * $ratio;
				}
				$dimArray = array("height"=>$th, "width"=>$tw);
				return $dimArray;
			}
			return "Error; not a correct image type.";
		}

		//Helper update methods for updateFood in ajaxMethods
		static public function insert_sales_table($id, $sale_price, $sale_start_date, $sale_end_date, $dbh){
			$query = "INSERT INTO food_sale_sales_history SET
                    food_id = '".$id."',
                    sale_price = '".$sale_price."',
                    sale_start_date = '".Functions::format_date_update($sale_start_date)."',
                    sale_end_date = '".Functions::format_date_update($sale_end_date)."'";
			$sql = $dbh->prepare($query);
			$sql->execute();
		}

		/* Type functions ------------------------------------------------------------------------------------------------------------------------------------------------------*/
		public static function fetch_existing_types($id, $dbh){
			$existing_types = array();
			$query = "
                   SELECT * FROM food_sale_food_type_match
                   WHERE food_id = ".$id."
                   ";
			$sql = $dbh->prepare($query);
			$sql->execute();

			foreach($sql as $row){
				array_push($existing_types, $row['type_id']);
			}

			return $existing_types;
		}

		public static function fetch_all_type_matches($dbh){
			$all_types = array();
			$query = "SELECT * FROM food_sale_food_type_match";

			$sql = $dbh->prepare($query);
			$sql->execute();

			foreach($sql as $row){
				array_push($all_types, $row['type_id']);
			}

			return $all_types;
		}

		public static function fetch_all_types($dbh){
			$query = "SELECT * FROM food_sale_type";
			$sql = $dbh->prepare($query);
			$sql->execute();

			//Assigns $row to entire result set of query.
			$row = $sql->fetchAll();

			return $row;
		}

		//Update types.
		public static function update_type_table($array, $existing_types, $id, $dbh){
			//Contains any items that are to be removed. (Contained in the database, but were not included in the POST, so should be removed.)
			$delete_array = array_values(array_diff($existing_types,  $array));

			if(count($delete_array > 0)){
				for($x = 0; $x < count($delete_array); $x++){
					if($delete_array[$x] != 0){
						$query = "
                        DELETE FROM food_sale_food_type_match WHERE food_id = '".$id."'
                        AND type_id = '".$delete_array[$x]."'
                    ";
						$sql = $dbh->prepare($query);
						$sql->execute();
					}
				}
			}

			//array_diff gives me all values that are unique to the first parameter array.
			//array_values resets the indexes. array_diff keeps the old indexes of the previous arrays checked
			$add_array = array_values(array_diff($array, $existing_types));

			//Insert to db every entry in the array.
			if(count($add_array > 0)){
				for($x = 0; $x < count($add_array); $x++){
					if($add_array[$x] != ''){
						$query = "
                        INSERT INTO food_sale_food_type_match SET
                        food_id = '".$id."',
                        type_id = '".$add_array[$x]."'
                    ";
						$sql = $dbh->prepare($query);
						$sql->execute();
					}
				}
			}
		}

		public static function insert_type_table($array, $id, $dbh){
			for($x = 0; $x < count($array); $x++){
				$query = "
							INSERT INTO food_sale_food_type_match SET
							food_id = '".$id."',
							type_id = '".$array[$x]."'
				";
				$sql = $dbh->prepare($query);
				$sql->execute();
			}
		}

	}
?>