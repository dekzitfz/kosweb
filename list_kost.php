<?php
include "config.php";

$status = "";
$json = array();
$facility;
$rent_type;
$result;

$query = mysqli_query($conn, "SELECT * FROM kost");
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
echo json_encode($result);
?>