<?php
include "config.php";

$kost_id = $_GET['kost_id'];
$status = "";
$json = array();
$facility;
$rent_type;
$result;

$query = mysqli_query($conn, "SELECT * FROM kost WHERE kost_id='$kost_id'");
if (mysqli_num_rows($query) > 0) {
	//ada data
	$status = "success";
	while($row = $query->fetch_assoc()) {
		$qFacility = mysqli_query($conn, "SELECT * FROM facility WHERE facility_kost_id='$row[facility_kost_id]'");
		while($rowF = $qFacility->fetch_assoc()){
			$facility[] = $rowF;
		}

		$qRentType = mysqli_query($conn, "SELECT * FROM rent_type WHERE rent_type_kost_id='$row[rent_type_kost_id]'");
		while($rowR = $qRentType->fetch_assoc()){
			$rent_type[] = $rowR;
		}

		$json = array('kost_name' => $row['kost_name'],
			'kost_owner' => $row['kost_owner'],
			'kost_rooms' => $row['kost_rooms'],
			'kost_address' => $row['kost_address'],
			'kost_phone' => $row['kost_phone'],
			'kost_latitude' => $row['kost_latitude'],
			'kost_longitude' => $row['kost_longitude'],
			'kost_created_by' => $row['kost_created_by'],
			'kost_created_at' => $row['kost_created_at'],
			'kost_img' => $row['kost_img'],
			'facilities' => $facility,
			'rent_type' => $rent_type);
		$result = array('code' => 200,'status' => $status,'result' => $json);
	}
}else{
	//data kosong
	$status= "empty";
	$result = array('code' => 200,'status' => $status,'result' => $json);
}
echo json_encode($result);
?>