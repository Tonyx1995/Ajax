<?php
	require("../PHP/db.php");
	include("../PHP/functions.php");

	if(isset($_POST['food_id'])) $id = $_POST['food_id'];
	if(isset($_POST['type'])) $type = $_POST['type']; else $type = null;

	echo "
       <h3>Files uploaded must be less than 2MB large, and be a .jpg, .png, or .gif</h3>
       <br />
       <form method='post' id='upload_form' enctype='multipart/form-data'>
			 <input type='hidden' name='type' value='".$type."' />
			 <input type='hidden' name='food_id' value='".$id."' />
			 <div class='form-group padded-form'>
				 <label for='filename'>Select File:</label>
				 <input type='file' id='filename' name='filename' size='30' />
			 </div>
			 <div class='form-group'>
				 <input class='btn btn-primary' type='submit' value='Upload Picture'>
			 </div>
       </form>
       ";

	//For loading. (We populate it with jQuery during the actual submission of the form)
	echo "
		<div class='loading'>
			
		</div>
	";
?>