<?php
    session_start();
    require("lib/PHP/db.php");
    include("lib/PHP/functions.php");

    //Library that simulates the PHP 5.5+ password_hash function, but for older versions.
    require("lib/PHP/password.php");

    //Get values from ajax.
    if(isset($_POST['username'])) $username = $_POST['username']; else $username = null;
    if(isset($_POST['food_name'])) $food_name = $_POST['food_name']; else $food_name = null;
    if(isset($_POST['food_description'])) $food_description = $_POST['food_description']; else $food_description = null;
    if(isset($_POST['cyclone_card'])) $cyclone_card = $_POST['cyclone_card']; else $cyclone_card = "No";
    if(isset($_POST['cyclone_card_price'])) $cyclone_card_price = $_POST['cyclone_card_price']; else $cyclone_card_price = 0;
    if(isset($_POST['regular_price'])) $regular_price = $_POST['regular_price']; else $regular_price = 0;
    if(isset($_POST['sales'])) $sales = $_POST['sales']; else $sales = null;
    if(isset($_POST['sale_price'])) $sale_price = $_POST['sale_price']; else $sale_price = null;

    //Placeholder for last inserted record id.
    $lastID = 0;

    //If sale is set, initialize variables and format the dates. If not, null the variables
    if(isset($_POST['sale_start_date']) && $_POST['sales'] != "off"){
        $sale_start_date = $_POST['sale_start_date'];
        $sale_end_date = $_POST['sale_end_date'];
        $current_date = date("Y-m-d");
        $current_date = date("Y-m-d", strtotime($current_date));
        $sale_start_date = date("Y-m-d", strtotime($sale_start_date));
        $sale_end_date = date("Y-m-d", strtotime($sale_end_date));
    }else{
        $sale_start_date = null;
        $sale_end_date = null;
        $current_date = null;
    }

    $action = $_GET['action'];

    if(isset($_GET['id'])) $id = $_GET['id'];

    if (isset($_POST['password'])){
        $password = $_POST['password'];
        //Hash password using included library.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    //Register user if name is not taken.
    if($action == "register"){
        //Check for existing user first.
        $sql = $dbh->prepare("SELECT * FROM user WHERE username = '".$username."'");
        $sql->execute();
        $count = $sql->rowCount();

        if($count > 0){
            //Name taken.
            echo "<h3>Username already taken; try a new one.</h3>";
        }else {
            //Prepare the insert.
            $sql = $dbh->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
            $sql->bindParam(':username', $username);
            $sql->bindParam(':password', $hashed_password);

            //Execute.
            $sql->execute();

            //Confirmation message.
            echo "<h3>Thank you for registering with us, " . $username . "!</h3>";
        }
    }

    //Checking login credentials using included library.
    if($action == "login"){
        $query = "SELECT * FROM user WHERE username = '".$username."'";
        $sql = $dbh->prepare($query);
        $sql->execute();

        //Fetch returns a single row.
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        //Using the password_compat library to compare stored password to the one posted to verify information.
        if(password_verify($password, $row['password'])){
            echo "<h3>You are now logged in.</h3>";
            $_SESSION['username'] = $username;

            //Check for admin status
            if($row['admin'] == '1'){
                $_SESSION['admin'] = true;
            }
        }else{
            echo "<h3>Wrong information; try again.</h3>";
        }
    }

    //The reason I'm not echoing you are logged out right here is because I'm not using a post call for this function.
    //I'm using an ajax call, which requires a success callback function; that's where I'm displaying that message.
    if($action == "logout"){
        session_destroy();
    }

    //Retrieving dates.
    if($action == "getDates"){
        $query = "SELECT sale_start_date, sale_end_date FROM food_sale_food WHERE food_id = '".$id."'";
        $sql = $dbh->prepare($query);
        $sql->execute();

        //Return our starting and ending dates to parse out in javascript.
        foreach($sql as $row){
            echo $row['sale_start_date'] . ";" . $row['sale_end_date'];
        }
    }

    //Get data from food object by ID. (Displays table row)
    if($action == "displayByID" && $id){
        $query = "
            SELECT food_id, food_name, food_description, regular_price, sale_price, sale_start_date, sale_end_date, on_sale, cyclone_card_price, pic FROM food_sale_food
            WHERE food_id = '".$id."'
			";
        $sql = $dbh->prepare($query);
        $sql->execute();

        foreach($sql as $row) {
            //For calculating if today's date is within the sale range.
            $current_date = date("Y-m-d");
            $current_date = date("Y-m-d", strtotime($current_date));
            $sale_start = date("Y-m-d", strtotime($row['sale_start_date']));
            $sale_end = date("Y-m-d", strtotime($row['sale_end_date']));

            if($row['pic']){
                $dimArray = array();
                $dimArray = Functions::getThumbnail("lib/Styles/pics/food-", $row['pic']);
                
                echo "<td class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/pics/food-".$row['pic']."?123' height='".$dimArray['height']."' width='".$dimArray['width']."'/></td>";
            }else{
                echo "<td class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/default.jpg' height='100px' width='100px' /></td>";
            }
            echo "
				<td class='name'>" . $row['food_name'] . "</td>
				<td class='displaySpace description'>" . $row['food_description'] . "</td>";
            if(($current_date >= $sale_start) && ($current_date <= $sale_end) && $row['on_sale'] == 1){
                echo "<td class='price' style='font-weight: bold; color: red;'><img class='img-rounded' src='lib/Styles/sale.jpg' height='45px' width='45px' /><br /><s style='color: black; font-weight: normal;'>$".sprintf('%0.2f', $row['regular_price'])."</s><br />$".sprintf('%0.2f', $row['sale_price'])."</td>";
            }else{
                echo"<td class='price'>$".sprintf('%0.2f', $row['regular_price'])."</td>";
            }
				echo"
				<td class='displaySpace cyclonePrice'>$" . sprintf("%.02f", $row['cyclone_card_price']) . "</td>
				<td>
					<button id='edit_food_button' class='btn btn-default btn-block' data-toggle='modal' data-target='#editFoodModal' data-id='" . $row['food_id'] . "'>
						<span class='glyphicon glyphicon-cog'></span>
					</button>
				</td>
				<td>
					 <button id='delete_food_button' class='btn btn-danger btn-block' data-toggle='modal' data-target='#deleteModal' data-id='".$row['food_id']."'>
				        <span class='glyphicon glyphicon-remove'></span>
					 </button>
				</td>
		      ";
        }
    }

    //Same as above function, but non table row return.
    if($action == "displayByIDNonTable" && $id){
        $query = "
                SELECT food_id, food_name, food_description, regular_price, sale_price, on_sale, cyclone_card_price, pic FROM food_sale_food
                WHERE food_id = '".$id."'
             ";
        $sql = $dbh->prepare($query);
        $sql->execute();

        foreach($sql as $row) {
            if($row['pic']){
                $dimArray = array();
                $dimArray = Functions::getThumbnail("lib/Styles/pics/food-", $row['pic']);

                echo "<span class='displaySpaceSmall'><img class='img-rounded' src='lib/Styles/pics/food-".$row['pic']."?123' height='".$dimArray['height']."' width='".$dimArray['width']."'/></span>";
            }
            echo "
                <h4>".$row['food_name'] . "</h4>
                <h4>" . $row['food_description'] . "</h4>
                <h4>Regular Price: $" . sprintf("%.02f", $row['regular_price']) . "</h4>
                <form role='form' class='form-horizontal' method='POST' id='deleteForm'>
                   <button type='submit' class='btn btn-danger'>Delete</button>
                </form>
                ";
        }
    }

    //Add new entry into DB.
    if($action == "addFood"){
        $query = "
        INSERT INTO food_sale_food SET
		  food_name = '".$food_name."',
		  food_description = '".$food_description."',
		  regular_price = '".$regular_price."',
		  sale_price = '".$sale_price."',
        sale_start_date = '".Functions::format_date_update($sale_start_date)."',
	 	  sale_end_date = '".Functions::format_date_update($sale_end_date)."',";

        if(($current_date >= $sale_start_date) && ($current_date <= $sale_end_date) && $sale_price > 0){
            $query .= " on_sale = '1', ";
        }else{
            $query .=" on_sale = '0', ";
        }

        if($cyclone_card_price){
            $query .= "cyclone_card_price = '".$cyclone_card_price."', ";
        }

        $query .= "cyclone_card_item = '".$cyclone_card."'";
        $sql = $dbh->prepare($query);
        $sql->execute();

        $lastID = $dbh->lastInsertId();

        if(isset($_POST['type'])){
            $type_select = array();
            foreach($_POST['type'] as $type){
                array_push($type_select, $type);
            }
            Functions::insert_type_table($type_select, $lastID, $dbh);
        }

        //Echoing the id to add to our JavaScript object.
        echo $lastID;
    }

    if($action == "delete" && $id){
        $query = "
        DELETE FROM food_sale_food
        WHERE food_id = '" . $id . "'
        ";

        $sql = $dbh->prepare($query);
        $sql->execute();
    }

    //Return only updated image
    if($action == "displayUploadedImage" && $id){
        $query = "SELECT pic, food_id FROM food_sale_food WHERE food_id = '".$id."'";
        $sql = $dbh->prepare($query);
        $sql->execute();

        //Fetch returns a single row.
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $dimArray = array();
        $dimArray = Functions::getThumbnail("lib/Styles/pics/food-", $row['pic']);
        //The reason for the random ?123 after the image is because of caching issues. If we left that out and kept the image name the exact same without a refresh,
        //we don't get the desired outcome and the image won't actually show the new uploaded one without a full page refresh.
        if($row['pic']){
            echo "<img class='img-rounded' src='lib/Styles/pics/food-".$row['pic']."?".rand()."' height='".$dimArray['height']."' width='".$dimArray['width']."'/>";
        }else{
            echo "<img class='img-rounded' src='lib/Styles/default.jpg' height='100px' width='100px' />";
        }
    }   

    //Return all types
    if($action == "displayAllTypes"){
        $query = "SELECT * FROM food_sale_type";
        $sql = $dbh->prepare($query);
        $sql->execute();

        echo "<h4>Types of food to search for</h4>";

        foreach($sql as $row){
            echo"<label class='checkbox-inline'><input type='checkbox' name='type[]' value='".$row['type_id']."'>".ucfirst(str_replace('_', ' ', $row['type_name']))."</label>";
            echo"<br/>";
        }
    }

    //Update item in DB
    if($action == "updateFood" && $id){
        $type_select = array();
        foreach($_POST['type'] as $type){
            array_push($type_select, $type);
        }

        $existing_types = Functions::fetch_existing_types($id, $dbh);
        //If there's already entries, need to do an update, not insert.
        if(count($existing_types) > 0){
            Functions::update_type_table($type_select, $existing_types, $id, $dbh);
        }else{
            Functions::insert_type_table($type_select, $existing_types, $id, $dbh);
        }

        if(ucfirst($cyclone_card) == "Yes" && $cyclone_card_price > 0){
            $cyclone_card = 1;
        }else{
            $cyclone_card = 0;
            $cyclone_card_price = 0;
        }

        $query = "UPDATE food_sale_food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					cyclone_card_item = '".$cyclone_card."',
					cyclone_card_price = '".$cyclone_card_price."',
					sale_price = '".$sale_price."',
					sale_start_date = '".Functions::format_date_update($sale_start_date)."',
					sale_end_date = '".Functions::format_date_update($sale_end_date)."',";
        if(($current_date >= $sale_start_date) && ($current_date <= $sale_end_date) && $sale_price > 0){
            $query .= " on_sale = '1' ";
        }else{
            $query .=" on_sale = '0' ";
        }

        $query.= "WHERE food_id = '".$id."'";
        $sql = $dbh->prepare($query);
        $sql->execute();

        echo "<h2>Item updated.</h2>";
        //echo "<h2>".$query."</h2>";
    }

    //Upload Image, or replace image.
    if($action == "upload" && $id){
        if(isset($_GET['type'])) $type = $_POST['type']; else $type = null;

        if ($_FILES){ // Check to see if the script has had any files posted to it
            //This is the literal filename.
            $name = $_FILES['filename']['name'];


            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $_FILES["filename"]["name"]);
            $extension = end($temp);
            if ((($_FILES["filename"]["type"] == "image/gif")
                  || ($_FILES["filename"]["type"] == "image/jpeg")
                  || ($_FILES["filename"]["type"] == "image/jpg")
                  || ($_FILES["filename"]["type"] == "image/pjpeg")
                  || ($_FILES["filename"]["type"] == "image/x-png")
                  || ($_FILES["filename"]["type"] == "image/png"))
               && ($_FILES["filename"]["size"] < 2000000) //This file limit is 2,000,000 bytes, or 2 megabytes.
               && in_array($extension, $allowedExts)){
                if ($_FILES["filename"]["error"] > 0){
                    echo "Error: " . $_FILES["filename"]["error"] . "<br>";
                }
                else{
                    //If-else structure for what format to save our image in. Assigning the current ID in the url as file name.
                    if($_FILES["filename"]["type"] == "image/jpeg"
                       || $_FILES["filename"]["type"] == "image/jpg"
                       || $_FILES["filename"]["type"] == "image/pjpeg"){
                        $id_name = $id . ".jpg";
                    }else if($_FILES["filename"]["type"] == "image/gif"){
                        $id_name = $id . ".gif";
                    }else if($_FILES["filename"]["type"] == "image/png"
                       || $_FILES["filename"]["type"] == "image/x-png"){
                        $id_name = $id . ".png";
                    }

                    //If type = special
                    if($type == "special"){
                        $query = "UPDATE food_sale_specials SET 
						              pic = '" . $id_name . "'
						              WHERE special_id = " . $id;
                        $sql = $dbh->prepare($query);
                        $sql->execute();

                        //Section for uploading locally into Styles/pics/specials
                        move_uploaded_file($_FILES['filename']['tmp_name'], "lib/Styles/pics/special-" . $id_name);

                        //Section for uploading onto the server
                        //move_uploaded_file($_FILES["filename"]["tmp_name"], "/srv/webapps.sctcc.edu/html/placement/html/upload/special-" . $id_name);
                    }else{
                        $query = "UPDATE food_sale_food SET 
						              pic = '" . $id_name . "'
						              WHERE food_id = " . $id;
                        $sql = $dbh->prepare($query);
                        $sql->execute();

                        //Section for uploading locally into Styles/pics/specials
                        move_uploaded_file($_FILES['filename']['tmp_name'], "lib/Styles/pics/food-" . $id_name);

                        //Section for uploading onto the server
                        //move_uploaded_file($_FILES["filename"]["tmp_name"], "/srv/webapps.sctcc.edu/html/placement/html/upload/food-" . $id_name);
                    }
                    echo "<h3>Image uploaded.</h3>";
                }
            }else{
                echo "<h2>Invalid file.</h2>";
            }
        }
    }

?>