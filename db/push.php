<?php


	function confirm($order, $item, $quantity, $size, $details, $price) {
		// Subject of confirmation email.
		$conf_subject = 'Order Confirmation';

		// Who should the confirmation email be from?
		$conf_sender = 'Pizza Deli <cyc025@uw.edu>';

		$total_cost = $quantity * $price;

		$msg = $_POST['Name'] . "Dear customer,
		
Your order confirmation number is $order.  
You have ordered the $quantity $size $item.
The total cost is \$$total_cost.
It should be ready in 30 minutes.

Thank you for your Order. Have a nice day!
		";

		$recipient_email = 'Pizza Deli <erniecyc@gmail.com>';

		mail( $recipient_email, $conf_subject, $msg, 'From: ' . $conf_sender );
	}

	function connect() {
		// Create connection
		$mysqli_connection = new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685);
		
		if($mysqli_connection->connect_error){
		   echo "Not connected, error: ".$mysqli_connection->connect_error;
		}

		return $mysqli_connection;
	}

	function createTable($conn) {
		$table = "CREATE TABLE IF NOT EXISTS pizza_order (
		id INT(6) AUTO_INCREMENT, 
		order_num INT(6) NOT NULL, 		
		item VARCHAR(45) NOT NULL,
		reg_date TIMESTAMP,
		quantity INT(5),
		size VARCHAR(20),
		detail VARCHAR(200),
		price DOUBLE(5,2) NOT NULL,
		completed TINYINT(1),
		PRIMARY KEY (id))";

	    if ($conn->query($table) === FALSE) {
		    echo "Error creating table: " . $conn->error;
		}
	}

	function insert($conn , $order, $item, $quantity, $size, $details, $price) 
    {
		$sql = "INSERT INTO pizza_order (ORDER_NUM, ITEM, QUANTITY, SIZE, DETAIL, PRICE, COMPLETED)
		VALUES ($order, \"$item\", $quantity, \"$size\", \"$details\", $price, 0)";

		if ($conn->query($sql) === TRUE) {
		    echo "Successfully Added";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
		$conn->close();
	}
	// The global $_POST variable allows you to access the data sent with the POST method
	// To access the data sent with the GET method, you can use $_GET
	$order = htmlspecialchars($_POST["order"]); 
	$item = htmlspecialchars($_POST["item"]); 
	$quantity  = htmlspecialchars($_POST["quantity"]); 
	$size  = htmlspecialchars($_POST["size"]); 
	$details  = htmlspecialchars($_POST["details"]); 
	$price  = htmlspecialchars($_POST["price"]); 

	$detail_string = str_replace(str_split('[],"'), '', (string)$details);

	$conn = connect();
    createTable($conn);
    insert($conn , $order, $item, $quantity, $size, $detail_string, $price);	
    confirm($order, $item, $quantity, $size, $detail_string, $price);
    Header('Content-type: text/xml');
    echo "<result>success</result>";
?>
