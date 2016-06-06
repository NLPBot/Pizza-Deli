<?php

$tmpFile = $_POST["order"] . ".wav";

//This is the directory where audio files will be saved
$target = "audio/";
$target = $target . basename( $tmpFile);

//Writes the Filename to the server
if(move_uploaded_file($_FILES["name"]["tmp_name"], $target)) {
    //Tells you if its all ok
  $msg = "audio saved";
} 
else 
{
  $msg = "unable to save audio";
}
Header('Content-type: text/xml');
echo "<result>". $msg ."</result>";

?>
