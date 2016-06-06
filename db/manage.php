<!DOCTYPE html>
<html>
<head>
<h2>
Pizza Express Pending Orders
</h2>
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
<p>
	<form action="http://students.washington.edu/cyc025/db/display.php">
	    <input type="submit" value="View All Orders">
	</form>
</p>

<!--
<p>
	<form action="http://students.washington.edu/cyc025/db/upload.php">
	    <input type="submit" value="Add Audio File">
	</form>
</p>

<footer>
	<form action="http://students.washington.edu/cyc025/db/Sign-In.html">
	    <input type="submit" value="Logout">
	</form>
</footer>
-->
</html>
<?php
	function connect() {
		// Create connection
		$mysqli_connection = new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685);
		
		return $mysqli_connection;
	}
	
	function parse_detail($s) {
	    if (strpos($s, 'deliver') !== false) {
	        return "address";
	    }
	    if (strpos($s, 'pickup') !== false) {
	        return "name";
	    }
	    return '';
	}
 
	function display($conn) {
		$sql = "SELECT * FROM pizza_order ORDER BY id";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			echo "<table><tr bgcolor=\"#819FF7\"><th>Order Number</th><th>Item</th><th>Quantity</th><th>Size</th><th>Detail</th><th>Name/Address</th><th>Price(\$)</th><th>Date</th><th>Complete</th><th>Cancel</th></tr>";
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		       $date = date("j F Y H:i:s", strtotime($row["reg_date"]));
		       $detail_string = str_replace(str_split('[],"'), '', (string)$row["detail"]);
		       if ($row["completed"] == 0) {
			       echo  "<tr><td align=\"center\">" . ($row["order_num"]) ."</td><td align=\"center\">". ($row["item"]) ."</td><td align=\"center\">". ($row["quantity"]) ."</td><td align=\"center\">". ($row["size"]) ."</td><td>";
			       
			       $button_name = parse_detail($detail_string);
			       $audio_file = "temp";
			       
                   $pieces = explode(" ", $detail_string);
                   $url = end($pieces);
                   $audio_file = str_replace("/cyc025/db/record/","",parse_url($url, PHP_URL_PATH));
                   

		           
		           if ( strcmp($button_name,'')!=0 ) {	   
		           
		                echo (str_replace($url,"",$detail_string)) ;  
		                      
		               echo "</td><td align=\"center\">";
		               echo "<audio controls style=\"width: 150px;\"> <source src=\"http://students.washington.edu/cyc025/db/audio/$audio_file\" type=\"audio/mpeg\">Your browser does not support the audio element.
</audio>";
		           }
		           else {

		                echo ($detail_string) ;
		                		                echo "</td><td>";
		           }

			       
			       
			       echo "</td><td align=\"right\">". ($row["price"])  ."</td><td align=\"center\">". ($date) . "</td><td>" ;
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





