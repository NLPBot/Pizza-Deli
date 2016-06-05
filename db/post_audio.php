
<?php
Header('Content-type: text/xml');

$msg = "";
$code = 1;

$tmpFile=$_POST['name'];
$tmpFile=$_POST['address'];

if (is_uploaded_file($tmpFile)) 
{
  $msg = "audio saved";
} 
else 
{
  $msg = "unable to save audio";
}

echo "<result>". $msg ."</result>";

/*
echo "<vxml version="2.0">";
echo "<form>";
echo "<var name=\"code\" expr=\".$code\"/>";
echo "<var name=\"msg\" expr=\"'.$msg'\"/>";
echo "<block>";
echo "<return namelist=\"code msg\"/>";
echo "</block></form></vxml>";
*/
?>
