<?php
	require("lib/PHP/functions.php");

	/*
	 *  How to get image width/height
	 *  Function returns an associative array of height and width
	*/
	$test = array();
	$test = Functions::getThumbnail("blah blah directory", "lib/Styles/loading.gif");

	//print_r($test);
	//echo ($test['height']);
	//echo ($test['width']);
	//---------------------------------------------------------------------------------------------------------------------------------------------------------

?>