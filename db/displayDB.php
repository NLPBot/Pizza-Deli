<!DOCTYPE html>
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
	 setInterval('autoRefresh()', 10000); // this will reload page after every 5 secounds; 
</script>
<body>
<!--
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
		<b><label for='date'>Select Date</label><br></b>
		<select name="date">
			<option value=""></option>  
			<option value="today">today</option>  
		  	<option value="yesterday">yesterday</option>  
		</select>
		<input type="submit" name="formSubmit" value="Submit" />
	</form>
	-->
<div>
<!--
<footer>
	<form action="http://students.washington.edu/cyc025/db/Sign-In.html">
	    <input type="submit" value="Logout">
	</form>

</footer>
-->
</div>
</body>

</html>
<?php
	
	function connect() {
		// Create connection
		$mysqli_connection = new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685);
		
		return $mysqli_connection;
	}

	function display($conn) {
		$sql = "SELECT * FROM pizza_order ORDER BY order_num";
		$result = mysqli_query($conn, $sql);

		$total_orders = 0;

		if (mysqli_num_rows($result) > 0) {

			echo "<table><tr bgcolor=\"#819FF7\"><th>Order Number</th><th>Item</th><th>Date</th><th>Quantity</th><th>Size</th><th>Detail</th><th>Price(\$)</th><th>Completed</th></tr>";
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		       $detail_string = str_replace(str_split('[],"'), '', (string)$row["detail"]);
		       echo  "<tr><td>" . ($row["order_num"]) ."</td><td>". ($row["item"]) ."</td><td>". ($row["reg_date"]) ."</td><td>". ($row["quantity"]) ."</td><td>". ($row["size"]) ."</td><td>". ($row["detail"]) ."</td><td>". ($row["price"]) . "</td><td>". ($row["completed"]) ."</td></tr>";	
		    }
		    echo "</table>";
		} else {
		    echo "0 results";
		}
	}
	$conn = connect();
	display($conn);
?>





