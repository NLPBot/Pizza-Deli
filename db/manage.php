<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
     border: 1px solid black;
}
</style>
</head>
<script>
	function autoRefresh()
	{
		window.location = window.location.href;
	}
	 setInterval('autoRefresh()', 5000); // this will reload page after every 5 secounds; 
</script>
<p>
	<form action="http://students.washington.edu/cyc025/db/display.php">
	    <input type="submit" value="View All Transactions">
	</form>
</p>
<!--
<footer>
	<form action="http://students.washington.edu/cyc025/db/Sign-In.html">
	    <input type="submit" value="Logout">
	</form>
</footer>
-->
</html>
<?php
	// Turn off all error reporting
	/*error_reporting(0);
    if(!isset($_SESSION['userName'])) {
			$url = "http://students.washington.edu/cyc025/db/Sign-In.html";
			header('Location: ' . $url);
	}*/
	function connect() {
		// Create connection
		$mysqli_connection = new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685);
		
		return $mysqli_connection;
	}
 
	function display($conn) {
		$sql = "SELECT * FROM pizza_order ORDER BY order_num";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			echo "<table><tr bgcolor=\"#819FF7\"><th>Order Number</th><th>Item</th><th>Date</th><th>Quantity</th><th>Size</th><th>Detail</th><th>Price</th><th>Complete Order</th><th>Cancel Order</th></tr>";
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		       $date = date("jS F, Y H:i:s", strtotime($row["reg_date"]));
		       $detail_string = str_replace(str_split('[],"'), '', (string)$row["detail"]);
		       if ($row["completed"] == 0) {
			       echo  "<tr><td>" . ($row["order_num"]) ."</td><td>". ($row["item"]) ."</td><td>". ($date) ."</td><td>". ($row["quantity"]) ."</td><td>". ($row["size"]) ."</td><td>". ($detail_string) ."</td><td>". ($row["price"]) . "</td><td>" ;
			       $id = $row["id"];
			       echo "<form action=\"http://students.washington.edu/cyc025/db/complete_order.php\" method=\"post\">
			       		<input type=\"hidden\" name=\"id\" value=$id>
						<button>Complete</button>
			       		</form>";

			       echo "</td><td>";

			       echo "<form action=\"http://students.washington.edu/cyc025/db/delete_order.php\" method=\"post\">
			       		<input type=\"hidden\" name=\"id\" value=$id>
						<button>Cancel</button>
			       		</form>";			       

				   echo "</td></tr>";	

				}       
		    }
		    echo "</table>";
		} else {
		    echo "0 results";
		}
	}

	$conn = connect();
	display($conn);
?>





