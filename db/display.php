<?php
	$cookie_num = 0;
	$cookie_row_user = "rowUser";
	if ( !isset($_COOKIE[$cookie_row_user])) {
		setcookie($cookie_row_user, 0, time() + (86400 * 30), "/"); // 86400 = 1 day
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
		setcookie($cookie_row_user, $cookie_num, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	else {
		$cookie_num = $_COOKIE[$cookie_row_user];
	}

	$cookie_date_user = "dateUser";
	$cookie_date = 0;
	if ( !isset($_COOKIE[$cookie_row_user])) {
		setcookie($cookie_date_user, 0, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	if(isset($_POST['date']) )
	{
		if ( $_POST['date'] == "all" ) {
			$cookie_date = -1;
		}
		if ( $_POST['date'] == "today" ) {
			$cookie_date = 0;
		}
		if ( $_POST['date'] == "yesterday" ) {
			$cookie_date = 1;
		}			
		setcookie($cookie_date_user, $cookie_date, time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	else {
		$cookie_date = $_COOKIE[$cookie_date_user];
	}

?>
<!DOCTYPE html>
<head>
<h2>
Pizza Express Orders
</h2>
<style type="text/css">
table, th, td {
     border: 1px solid black;
}
label {
  display: inline-block;
  width: 140px;
  text-align: left;
}​
.container input {
    width: 100%;
    clear: both;
  display: inline-block;
}
</style>
</head>
<p>
    <form action="http://students.washington.edu/cyc025/db/manage.php">
        <input type="submit" value="View Pending Orders">
    </form>
</p>
<script>
	function autoRefresh()
	{
		window.location = window.location.href;
	}
	 setInterval('autoRefresh()', 100000); // this will reload page after every 5 secounds; 
</script>
<body>
<div class="container">

	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
		
		<b><label for='formRows'>Select Rows</label></b>
		<select name="formRows">
			<option value=""></option>  
		  	
		  	<option value="10">10</option>
		  	<option value="20">20</option>
		  	<option value="all">all</option>  
		</select>
		<input type="submit" name="formSubmit" value="Submit" />
		
	</form>

	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
		<b><label for='date'>Select Day</label></b>
		<select name="date">
		    <option value=""></option>  
			
			<option value="today">today</option>  
		  	<option value="yesterday">yesterday</option>  
		  	<option value="all">all</option>
		</select>
		<input type="submit" name="formSubmit" value="Submit" />
	</form>

	</div>
    <div>
    <br>
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

	function is_today($to_be_compared) {
		// set the default timezone to use. Available since PHP 5.1
		date_default_timezone_set('UTC');
		if ( strcmp(date("j F Y"),$to_be_compared)==0 ) {
			return True;
		}
	}

	function is_yesterday($to_be_compared) {
		// set the default timezone to use. Available since PHP 5.1
		date_default_timezone_set('UTC');
		if ( strcmp(date("j F Y",strtotime("-1 days")), $to_be_compared)==0 ) {
			return True;
		}
	}

	function get_most_popular_item($items) {
		$c = array_count_values($items); 
		$val = array_search(max($c), $c);
		return $val;
	}

	function display_summary($conn,$cookie_num,$cookie_date) {
		$sql = "SELECT * FROM pizza_order ORDER BY order_num DESC, id DESC";
		$result = mysqli_query($conn, $sql);

		$total_cost = 0.;
		$total_orders = 0;

		$complete_orders = 0;
		$incomplete_orders = 0;

		$items_list = array();

		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
		       if( $total_orders == $cookie_num ) {
		       		break;
		       }
		       $total_orders = $total_orders + 1;

		       if( $cookie_date==0 && !is_today(date("jS F, Y", strtotime($row["reg_date"]))) ) {
		       		continue;
		       }
		       if( $cookie_date==1 && !is_yesterday(date("jS F, Y", strtotime($row["reg_date"]))) ) {   
		       		continue;
		       }

		       array_push($items_list, $row["item"]);
		       
		       $date = date("j F Y H:i:s", strtotime($row["reg_date"]));  
		       if ($row["completed"]==1) {
		       		$complete = "yes";	
		       		$complete_orders = $complete_orders + 1;	
		       }
		       else{
		       		$complete = "no";
		       		$incomplete_orders = $incomplete_orders + 1;
		       }
		    }
		    echo "<b> <p> Summary:";
		    echo " $total_orders total orders, $incomplete_orders pending orders </p> </b>";
			//$most_popular = get_most_popular_item($items_list);
			//echo "<b> <p> $most_popular is the most popular item  </p> </b>";

		}
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
	
	function display($conn,$cookie_num,$cookie_date) {
		display_summary($conn,$cookie_num,$cookie_date);

		$sql = "SELECT * FROM pizza_order ORDER BY id DESC";
		$result = mysqli_query($conn, $sql);

		$total_cost = 0.;
		$total_orders = 0;

		$complete_orders = 0;
		$incomplete_orders = 0;

		if (mysqli_num_rows($result) > 0) {

			echo "<table><tr bgcolor=\"#819FF7\"><th>Order Number</th><th>Item</th><th>Quantity</th><th>Size</th><th>Detail</th><th>Name/Address</th><th>Price(\$)</th><th>Date</th><th>Complete</th></tr>";
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		       if( $total_orders == $cookie_num ) {
		       		break;
		       }	

		       $total_cost = $total_cost + $row["price"] * $row["quantity"];
		       $total_orders = $total_orders + 1;

		       if( $cookie_date==0 ) {
		       		if( !is_today(date("j F Y", strtotime($row["reg_date"]))) ) {
		       			continue;
		       		}
		       }
		       if( $cookie_date==1 ) {
		       		if( !is_yesterday(date("j F Y", strtotime($row["reg_date"]))) ) {
		       			continue;
		       		}
		       }

		       $date = date("j F Y H:i:s", strtotime($row["reg_date"]));
		       $detail_string = str_replace(str_split('[],"'), '', (string)$row["detail"]);
		       
		       if ($row["completed"]==1) {
		       		$complete = "yes";	
		       		$complete_orders = $complete_orders + 1;
		       		echo  "<tr><td align=\"center\">";
		       }
		       else{
		       		$complete = "no";
		       		$incomplete_orders = $incomplete_orders + 1;
		       		echo  "<tr bgcolor=\"#F6CECE\"><td align=\"center\">";
		       }
		       echo ($row["order_num"]) ."</td><td align=\"center\">". ($row["item"]) ."</td><td align=\"center\">". ($row["quantity"]) ."</td><td align=\"center\">". ($row["size"]) ."</td><td>";
			       
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
		               //<audio preload=\"auto\" src=\"http://students.washington.edu/cyc025/db/audio/$audio_file\" loop=\"true\" autobuffer> Unsupported in Firefox </audio>";
		               //<form action=\"http://students.washington.edu/cyc025/db/audio/$audio_file\" method=\"post\">
		               	//echo "<input type=\"hidden\" name=\"id\" value=$id style=\"float: right;\">
					     //   <button >$button_name</button>
		               		//</form>";
		           }
		           else {

		                echo ($detail_string) ;
		                		                echo "</td><td>";
		           }

			       
			       
			       echo "</td><td align=\"right\">". ($row["price"]) ."</td><td align=\"center\">". ($date) . "</td><td align=\"center\">". ($complete) ."</td></tr>";	
		    }
		    echo "</table>";
		} else {
		    echo "0 results";
		}
		/*echo "<p>Total Costs: \$$total_cost</p>";*/
	}
	$conn = connect();
	display($conn,$cookie_num,$cookie_date);
	echo "<br><br><br><br><br><br>";
?>


<!--
<p>
<br><br><br><br>
</p>
<p>
    <canvas id="myCanvas" width="578" height="300"></canvas>
    <script>
      function Graph(config) {
        // user defined properties
        this.canvas = document.getElementById(config.canvasId);
        this.minX = config.minX;
        this.minY = config.minY;
        this.maxX = config.maxX;
        this.maxY = config.maxY;
        this.unitsPerTick = config.unitsPerTick;

        // constants
        this.axisColor = '#aaa';
        this.font = '8pt Calibri';
        this.tickSize = 20;

        // relationships
        this.context = this.canvas.getContext('2d');
        this.rangeX = this.maxX - this.minX;
        this.rangeY = this.maxY - this.minY;
        this.unitX = this.canvas.width / this.rangeX;
        this.unitY = this.canvas.height / this.rangeY;
        this.centerY = Math.round(Math.abs(this.minY / this.rangeY) * this.canvas.height);
        this.centerX = Math.round(Math.abs(this.minX / this.rangeX) * this.canvas.width);
        this.iteration = (this.maxX - this.minX) / 1000;
        this.scaleX = this.canvas.width / this.rangeX;
        this.scaleY = this.canvas.height / this.rangeY;

        // draw x and y axis
        this.drawXAxis();
        this.drawYAxis();
      }

      Graph.prototype.drawXAxis = function() {
        var context = this.context;
        context.save();
        context.beginPath();
        context.moveTo(0, this.centerY);
        context.lineTo(this.canvas.width, this.centerY);
        context.strokeStyle = this.axisColor;
        context.lineWidth = 2;
        context.stroke();

        // draw tick marks
        var xPosIncrement = this.unitsPerTick * this.unitX;
        var xPos, unit;
        context.font = this.font;
        context.textAlign = 'center';
        context.textBaseline = 'top';

        // draw left tick marks
        xPos = this.centerX - xPosIncrement;
        unit = -1 * this.unitsPerTick;
        while(xPos > 0) {
          context.moveTo(xPos, this.centerY - this.tickSize / 2);
          context.lineTo(xPos, this.centerY + this.tickSize / 2);
          context.stroke();
          context.fillText(unit, xPos, this.centerY + this.tickSize / 2 + 3);
          unit -= this.unitsPerTick;
          xPos = Math.round(xPos - xPosIncrement);
        }

        // draw right tick marks
        xPos = this.centerX + xPosIncrement;
        unit = this.unitsPerTick;
        while(xPos < this.canvas.width) {
          context.moveTo(xPos, this.centerY - this.tickSize / 2);
          context.lineTo(xPos, this.centerY + this.tickSize / 2);
          context.stroke();
          context.fillText(unit, xPos, this.centerY + this.tickSize / 2 + 3);
          unit += this.unitsPerTick;
          xPos = Math.round(xPos + xPosIncrement);
        }
        context.restore();
      };

      Graph.prototype.drawYAxis = function() {
        var context = this.context;
        context.save();
        context.beginPath();
        context.moveTo(this.centerX, 0);
        context.lineTo(this.centerX, this.canvas.height);
        context.strokeStyle = this.axisColor;
        context.lineWidth = 2;
        context.stroke();

        // draw tick marks
        var yPosIncrement = this.unitsPerTick * this.unitY;
        var yPos, unit;
        context.font = this.font;
        context.textAlign = 'right';
        context.textBaseline = 'middle';

        // draw top tick marks
        yPos = this.centerY - yPosIncrement;
        unit = this.unitsPerTick;
        while(yPos > 0) {
          context.moveTo(this.centerX - this.tickSize / 2, yPos);
          context.lineTo(this.centerX + this.tickSize / 2, yPos);
          context.stroke();
          context.fillText(unit, this.centerX - this.tickSize / 2 - 3, yPos);
          unit += this.unitsPerTick;
          yPos = Math.round(yPos - yPosIncrement);
        }

        // draw bottom tick marks
        yPos = this.centerY + yPosIncrement;
        unit = -1 * this.unitsPerTick;
        while(yPos < this.canvas.height) {
          context.moveTo(this.centerX - this.tickSize / 2, yPos);
          context.lineTo(this.centerX + this.tickSize / 2, yPos);
          context.stroke();
          context.fillText(unit, this.centerX - this.tickSize / 2 - 3, yPos);
          unit -= this.unitsPerTick;
          yPos = Math.round(yPos + yPosIncrement);
        }
        context.restore();
      };

      Graph.prototype.drawEquation = function(equation, color, thickness) {
        var context = this.context;
        context.save();
        context.save();
        this.transformContext();

        context.beginPath();
        context.moveTo(this.minX, equation(this.minX));

        for(var x = this.minX + this.iteration; x <= this.maxX; x += this.iteration) {
          context.lineTo(x, equation(x));
        }

        context.restore();
        context.lineJoin = 'round';
        context.lineWidth = thickness;
        context.strokeStyle = color;
        context.stroke();
        context.restore();
      };

      Graph.prototype.transformContext = function() {
        var context = this.context;

        // move context to center of canvas
        this.context.translate(this.centerX, this.centerY);

        /*
         * stretch grid to fit the canvas window, and
         * invert the y scale so that that increments
         * as you move upwards
         */
        context.scale(this.scaleX, -this.scaleY);
      };
      var myGraph = new Graph({
        canvasId: 'myCanvas',
        minX: -10,
        minY: -10,
        maxX: 10,
        maxY: 10,
        unitsPerTick: 1
      });

      myGraph.drawEquation(function(x) {
        return x * x;
      }, 'blue', 3);

    </script>
</p>
-->





