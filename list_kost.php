<?php
include "config.php";


$status = "";
$json = array();
$facility;
$result;

$query = mysqli_query($conn, "SELECT * FROM kost");
if (mysqli_num_rows($query) > 0) {
	//ada data
	while($row = $query->fetch_assoc()) {
		$qFacility = mysqli_query($conn, "SELECT * FROM facility WHERE facility_kost_id='$row[facility_kost_id]'");
		while($rowF = $qFacility->fetch_assoc()){
			$facility[] = $rowF;
		}
		//$status= "success";
		$json[] = $row;
		$json = array('kost_name' => $row['kost_name'],
			'kost_owner' => $row['kost_owner'],
			'facilities' => $facility);
		//$row = array_push(array, var)
		$result = array('code' => 200,'result' => $json);
	}
}else{
	//data kosong
	$status= "empty";
}
echo json_encode($result);
?>