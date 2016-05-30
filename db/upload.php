<?php

//This is the directory where audio files will be saved
$target = "audio/";
$target = $target . basename( $_FILES['Filename']['name']);

//This gets all the other information from the form
$Filename=basename( $_FILES['Filename']['name']);
$Description=$_POST['Description'];


//Writes the Filename to the server
if(move_uploaded_file($_FILES['Filename']['tmp_name'], $target)) {
    //Tells you if its all ok
    echo "The file ". basename( $_FILES['Filename']['name']). " has been uploaded, and your information has been added to the directory";
    // Connects to your Database
    mysql_connect("localhost", "root", "") or die(mysql_error()) ;
    mysql_select_db("altabotanikk") or die(mysql_error()) ;

    //Writes the information to the database
    mysql_query("INSERT INTO picture (Filename,Description)
    VALUES ('$Filename', '$Description')") ;
} else {
    //Gives and error if its not
    echo "Sorry, there was a problem uploading your file.";
}
?>
<!DOCTYPE html>
<form method="post" action="upload.php" enctype="multipart/form-data">
    <p>Audio:</p>
    <input type="file" name="Filename"> 
    <p>Description</p>
    <textarea rows="10" cols="35" name="Description"></textarea>
    <br/>
    <input TYPE="submit" name="upload" value="Add"/>
</form>
</html>




