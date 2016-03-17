<?php
include "config.php";

$kost_id = "";
$kost_name = $_POST['kost_name'];
$kost_owner = $_POST['kost_owner'];
$kost_rooms = $_POST['kost_rooms'];
$kost_address = $_POST['kost_address'];
$kost_phone = $_POST['kost_phone'];
$kost_latitude = $_POST['kost_latitude'];
$kost_longitude = $_POST['kost_longitude'];
//$kost_created_by = $_POST['kost_created_by'];
$facility_kost_id = "";
$rent_type_kost_id = "";
$rent_type_name = $_POST['rent_type_name'];
$facility_name = $_POST['facility_name'];

$result = array();

//insert to table kost to get kost_id
$qInsertKost = "INSERT INTO kost (kost_id,
								  kost_name,
								  kost_owner,
								  kost_rooms,
								  kost_address,
								  kost_phone,
								  kost_latitude,
								  kost_longitude,
								  kost_created_by) 
					   VALUES (null,
					   		  '$kost_name',
					   		  '$kost_owner',
					   		  '$kost_rooms',
					   		  '$kost_address',
					   		  '$kost_phone',
					   		  '$kost_latitude',
					   		  '$kost_longitude',
					   		  'dekz')";
if(mysqli_query($conn, $qInsertKost)){
	//insert koss success
	//get kost_id in the last row
	$qGetLastKostID = mysqli_query($conn,"SELECT * FROM kost ORDER BY kost_id DESC LIMIT 1");
	if(mysqli_num_rows($qGetLastKostID) > 0){
		//succes get last row
		while($row = $qGetLastKostID->fetch_assoc()) {
			$kost_id = $row['kost_id'];
		}

		//get & insert facilities to facility table
		foreach ($facility_name as $key => $facility) {
			//insert facilities
			$qFacility = "INSERT INTO facility (facility_id, facility_name, facility_kost_id, kost_id) 
							   VALUES (null, '$facility', '', $kost_id)";
			mysqli_query($conn, $qFacility);
		}

		//get last facility_id to be used as facility_kost_id
		$qGetFacilityKostID = mysqli_query($conn, "SELECT * FROM facility ORDER BY facility_id DESC LIMIT 1");
		if(mysqli_num_rows($qGetFacilityKostID) > 0){
			//success get facility_id
			//update facility_kost_id
			while($row = $qGetFacilityKostID->fetch_assoc()) {
				$facility_kost_id = $row['facility_id'];
			}
			$qUpdateFacilityKostID = "UPDATE facility SET 
										facility_kost_id = '$facility_kost_id'
										WHERE kost_id = '$kost_id'";
			$qUpdateFacilityAtTableKost = "UPDATE kost SET 
										facility_kost_id = '$facility_kost_id'
										WHERE kost_id = '$kost_id'";
			if(mysqli_query($conn, $qUpdateFacilityKostID) && mysqli_query($conn, $qUpdateFacilityAtTableKost)){
				//success update facility
				//manage to insert rent_type

				foreach ($rent_type_name as $key => $rent) {
					$qRent = "INSERT INTO rent_type (rent_type_id, rent_type_name, rent_type_kost_id, kost_id) 
									   VALUES (null, '$rent', '', $kost_id)";
					mysqli_query($conn, $qRent);
				}
				$qGetRentTypeKostID = mysqli_query($conn, "SELECT * FROM rent_type ORDER BY rent_type_id DESC LIMIT 1");
				if(mysqli_num_rows($qGetRentTypeKostID) > 0){
					while($row = $qGetRentTypeKostID->fetch_assoc()) {
						$rent_type_kost_id = $row['rent_type_id'];
					}
					$qUpdateRentTypeKostID = "UPDATE rent_type SET 
												rent_type_kost_id = '$rent_type_kost_id'
												WHERE kost_id = '$kost_id'";
					$qUpdateRentTypeAtTableKost = "UPDATE kost SET 
												rent_type_kost_id = '$rent_type_kost_id'
												WHERE kost_id = '$kost_id'";
					if(mysqli_query($conn, $qUpdateRentTypeKostID) && mysqli_query($conn, $qUpdateRentTypeAtTableKost)){
						//$result['status'] = "success";
						//$result['message'] = "berhasil menambahkan kost";
						if($_SERVER['REQUEST_METHOD']=='POST'){
							$image = $_POST['image'];
							
							$path = "img/".$kost_name.".jpg";
							$img_name = "http://".$_SERVER['SERVER_NAME']."/".$path."";

							$sqlImage = "UPDATE kost SET 
											kost_img = '$img_name'
											WHERE kost_id = '$kost_id'";
							 
							if(mysqli_query($conn,$sqlImage)){
								file_put_contents($path,base64_decode($image));
							 	$result['status'] = "success";
								$result['message'] = "berhasil menambahkan kost";
							}else{
								$result['status'] = "failed";
								$result['message'] = "gagal mengupload gambar";
							}
						}else{
							$result['status'] = "failed";
							$result['message'] = "error saat mengupload gambar";
						}
					}else{
						//failed to update rent_type_kost_id
						$result['status'] = "failed";
						$result['message'] = "gagal mengupdate nilai rent_type_kost_id";
					}
				}else{
					//failed get last row rent_type
					$result['status'] = "failed";
					$result['message'] = "gagal mengambil nilai rent_type_id";
				}
			}else{
				//failed update facility
				$result['status'] = "failed";
				$result['message'] = "gagal mengupdate fasilitas";
			}
		}else{
			//failed get last facility_id
			$result['status'] = "failed";
			$result['message'] = "gagal mengambil nilai facility_id";
		}
	}else{
		//failed get last row to get kost_id
		$result['status'] = "failed";
		$result['message'] = "gagal mengambil nilai kost_id";
	}
}else{
	//failed insert kost
	$result['status'] = "failed";
	$result['message'] = "gagal menambahkan kost";
}
header('Content-Type: application/json');
echo json_encode($result);
?>