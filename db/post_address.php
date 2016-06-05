<?php
Header('Content-type: text/xml');

$msg = "";
$code = 1;

$tmpFile=$_POST[“order”];
$address = $_POST[“address”];

if (is_uploaded_file($address)) 
{
  $msg = "audio saved";
} 
else 
{
  $msg = "unable to save audio";
}

echo "<result>". $msg ."</result>";

?>