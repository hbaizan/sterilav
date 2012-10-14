<?php

function listaVehiculos() {
	global $conn;
	$query = "SELECT * FROM vehiculo";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idvehiculo'].'",';
		$result .= '"patente":"'.$row['vehiculo_patente'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getVehiculo($id) {
	global $conn;
	$query = "SELECT * FROM vehiculo WHERE idvehiculo = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Vehiculo."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idvehiculo'].'",';
			$result .= '"patente":"'.$row['vehiculo_patente'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putVehiculo() {
	global $conn;

	$response = "";
	$patente = chequearCampo($_POST['patente']);
	
	$query = "INSERT INTO vehiculo (vehiculo_patente) VALUES ('$patente')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El Vehiculo ha sido creado."}';
	}

	return $response;
}

function updateVehiculo() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$patente = chequearCampo($_POST['patente']);

	$query = "UPDATE vehiculo SET vehiculo_patente='$patente' WHERE idvehiculo = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El Vehiculo ha sido actualizado."}';
	}

	return $response;
}

?>