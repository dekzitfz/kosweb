<?php
include "config.php";

$surveyor_id = $_GET['surveyor_id'];
$status = "";
$json = array();
$facility;
$rent_type;
$result;

$query = mysqli_query($conn, "SELECT * FROM kost WHERE surveyor_id='$surveyor_id'");
if (mysqli_num_rows($query) > 0) {
	//ada data
	$status = "success";
	while($row = $query->fetch_assoc()) {
		$json[] = $row;
		$result = array('code' => 200,'status' => $status,'result' => $json);
	}
}else{
	//data kosong
	$status = "empty";
	$result = array('code' => 200,'status' => $status,'result' => $json);
}
header('Content-Type: application/json');
echo json_encode($result);
?>