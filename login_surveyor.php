<?php
include "config.php";

$username = $_POST['username'];
$password = md5($_POST['password']);
$json = array();

$cekUsername = "SELECT * FROM surveyor WHERE surveyor_username='$username'";
$rUsername = mysqli_query($conn, $cekUsername);

if (mysqli_num_rows($rUsername) > 0) {
	$cekpassword = "SELECT * FROM surveyor WHERE surveyor_username='$username' AND surveyor_password='$password'";
	$rPassword = mysqli_query($conn, $cekpassword);
	if(mysqli_num_rows($rPassword) > 0){
		while($row = $rUsername->fetch_assoc()) {
			$json = $row;
			$json['status'] = "success";
		}
	}else{
		$json['status'] = "failed";
		$json['message'] = "password salah";
	}	
}else{
	$json['status'] = "failed";
	$json['message'] = "username tidak ditemukan";
}

header('Content-Type: application/json');
echo json_encode($json);
?>