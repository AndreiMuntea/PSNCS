<?php
require 'session.php';
?>
<!DOCTYPE html>
<html>
<head>
<script src="jquery-3.3.1.js"></script>
</head>
<body>

<h1>Second page</h1> 

<form action="ping.php" method="get">
  IP Address:<br> <input type="text" name="ip">
  <input type="submit" value="Ping" name="submit">
</form>

<form action="/add_comment.php" method="post">
  Comment:<br> <input type="text" name="comment">
  <input type="submit">
</form>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

<br><br>

<div id="comments"></div>

<script>
window.onload = function(){ 
    $.get("get_comments.php", { }, function(data, status){
            $("#comments").html(data); 
        }
    );
}; 

</script>

</body>
</html>