<?php
include "config.php";

if($_SERVER['REQUEST_METHOD']=='POST'){
 
	$image = $_POST['image'];
	$img_name = $_POST['img_name'];
	$path = "img/babi.jpg";

	$sqlImage = "UPDATE kost SET 
					kost_img = '$img_name'
					WHERE kost_id = '1'";
	 
	if(mysqli_query($conn,$sqlImage)){
		file_put_contents($path,base64_decode($image));
	 	echo "Successfully Uploaded";
	}else{
		echo "failed upload";
	}
}else{
	echo "Error";
}
?>