<?php

	function get_data($fields) {
		$sql = "SELECT * FROM pizza_order";
		$result = mysqli_query(new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685), $sql);

		if (mysqli_num_rows($result) > 0) {
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		    	while($field = $fields) {
		    	    $data[$field] = $row[$field];
		   		}
		    }
		} else {
		    echo "0 results";
		}
	}

	// The global $_POST variable allows you to access the data sent with the POST method
	$item = htmlspecialchars($_POST["item"]); 
	$quantity  = htmlspecialchars($_POST["quantity"]); 
	$size  = htmlspecialchars($_POST["size"]); 
	$details  = htmlspecialchars($_POST["details"]); 
	$price  = htmlspecialchars($_POST["price"]); 
	$recording  = htmlspecialchars($_POST["recording"]); 
	$fields = array( $item, $quantity, $size, $details, $price, $recording );




?>