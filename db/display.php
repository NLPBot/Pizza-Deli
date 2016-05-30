<?php
	$cookie_num = 0;
	$cookie_user = "user";
	if ( !isset($_COOKIE[$cookie_user])) {
		setcookie($cookie_user, 0, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	if(isset($_POST['formRows']) )
	{
		
		if ( $_POST['formRows'] == "10" ) {
			$cookie_num = 10;
		}
		if ( $_POST['formRows'] == "20" ) {
			$cookie_num = 20;
		}			
		if ( $_POST['formRows'] == "all" ) {
			$cookie_num = -1;
		}
		setcookie($cookie_user, $cookie_num, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	else {
		$cookie_num = $_COOKIE[$cookie_user];
	}
?>
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
	 setInterval('autoRefresh()', 5000); // this will reload page after every 5 secounds; 
</script>
<body>
<center>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
		<b><label for='formRows'>Select Number of Rows</label><br></b>
		<select name="formRows">
			<option value=""></option>  
		  	<option value="all">all</option>  
		  	<option value="10">10</option>
		  	<option value="20">20</option>
		</select>
		<input type="submit" name="formSubmit" value="Submit" />
	</form>
</center>
<div>
<p>
	<form action="http://students.washington.edu/cyc025/db/manage.php">
	    <input type="submit" value="Manage Orders">
	</form>
</p>
<p>
</p>
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
 
	function display($conn,$cookie_num) {
		$sql = "SELECT * FROM pizza_order ORDER BY order_num";
		$result = mysqli_query($conn, $sql);

		$total_cost = 0.;
		$total_orders = 0;

		$complete_orders = 0;
		$incomplete_orders = 0;

		$cookie_user = "user";

		if (mysqli_num_rows($result) > 0) {

			echo "<table><tr bgcolor=\"#819FF7\"><th>Order Number</th><th>Item</th><th>Date</th><th>Quantity</th><th>Size</th><th>Detail</th><th>Price</th><th>Completed</th></tr>";
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		       if( $total_orders == $cookie_num ) {
		       		break;
		       }	

		       $total_cost = $total_cost + $row["price"] * $row["quantity"];
		       $total_orders = $total_orders + 1;

		       $date = date("jS F, Y H:i:s", strtotime($row["reg_date"]));
		       $detail_string = str_replace(str_split('[],"'), '', (string)$row["detail"]);
		       
		       if ($row["completed"]==1) {
		       		$complete = "yes";	
		       		$complete_orders = $complete_orders + 1;
		       		echo  "<tr><td>" . ($row["order_num"]) ."</td><td>". ($row["item"]) ."</td><td>". ($date) ."</td><td>". ($row["quantity"]) ."</td><td>". ($row["size"]) ."</td><td>". ($detail_string) ."</td><td>". ($row["price"]) . "</td><td>". ($complete) ."</td></tr>";	
		       }
		       else{
		       		$complete = "no";
		       		$incomplete_orders = $incomplete_orders + 1;
		       		echo  "<tr bgcolor=\"#F6CECE\"><td>" . ($row["order_num"]) ."</td><td>". ($row["item"]) ."</td><td>". ($date) ."</td><td>". ($row["quantity"]) ."</td><td>". ($row["size"]) ."</td><td>". ($detail_string) ."</td><td>". ($row["price"]) . "</td><td>". ($complete) ."</td></tr>";
		       }

		    }
		    echo "</table>";
		} else {
		    echo "0 results";
		}
		echo "<b> <p>Total Number of Orders: $total_orders</p> </b>";
		echo "<b> <p>Incomplete Orders: $incomplete_orders</p> </b>";
		/*echo "<p>Total Costs: \$$total_cost</p>";*/
	}
	$conn = connect();
	display($conn,$cookie_num);
?>





