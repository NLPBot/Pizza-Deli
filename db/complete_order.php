<?php
    if(!isset($_SESSION['userName'])) {
			$url = "http://students.washington.edu/cyc025/db/Sign-In.html";
			header('Location: ' . $url);
	}
	function connect() {
		// Create connection
		$mysqli_connection = new MySQLi('vergil.u.washington.edu', 'root', 'ernie', 'ordering_system', 8685);
		
		return $mysqli_connection;
	}
	$conn = connect();
	$id = htmlspecialchars($_POST["id"]); 
	$sql = "UPDATE pizza_order SET COMPLETED = 1 WHERE id = {$id}";
	$result = mysqli_query($conn, $sql);
	$url = "http://students.washington.edu/cyc025/db/manage.php";
	header('Location: ' . $url, false, 302);
?>