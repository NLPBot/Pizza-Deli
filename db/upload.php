<?php
//This is the directory where audio files will be saved
$target = "audio/";
$target = $target . basename( $_FILES['Filename']['name']);

//This gets all the other information from the form
$Filename=basename( $_FILES['Filename']['name']);

//Writes the Filename to the server
if(move_uploaded_file($_FILES['Filename']['tmp_name'], $target)) {
    //Tells you if its all ok
    echo "The file ". basename( $_FILES['Filename']['name']). " has been uploaded, and your information has been added to the directory";
} 
?>
<!DOCTYPE html>
<form method="post" action="upload.php" enctype="multipart/form-data">
    <p>Upload Audio File:</p>
    <input type="file" name="Filename"> 
    <br/><br/><br/><br/><br/>
    <input TYPE="submit" name="upload" value="Add"/>
</form>
</html>




