<?php  
define('DB_HOST', 'vergil.u.washington.edu:8685'); 
define('DB_NAME', 'ordering_system'); 
define('DB_USER','root'); 
define('DB_PASSWORD','ernie'); 
$con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error());
$db=mysql_select_db(DB_NAME,$con) or die("Failed to connect to MySQL: " . mysql_error());

function SignIn() { 
	session_start(); 
	if(!empty($_POST['user'])) 
	{ 

		$query = mysql_query("SELECT * FROM UserName where userName = '$_POST[user]' AND pass = '$_POST[pass]'"); 
		$row = mysql_fetch_array($query);
		
		if(!empty($row['userName']) AND !empty($row['pass'])) 
		{ 
			$_SESSION['userName'] = $row['userName']; 
			$url = "http://students.washington.edu/cyc025/db/display.php";
			header('Location: ' . $url, false, 302);
		} 
		else 
		{ 
			// destroy the session 
			$url = "http://students.washington.edu/cyc025/db/Sign-In.html";
			header('Location: ' . $url, false, 302);
		} 
		
	} 

} 
if(isset($_POST['submit'])) { 
	SignIn(); 
} 
?>
