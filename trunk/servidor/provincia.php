<?php

function listaProvincias() {
	global $conn;
	$query = "SELECT * FROM provincia";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Provincias."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idprovincia'].'",';
			$result .= '"descripcion":"'.$row['nombre'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getProvincia($id) {
	global $conn;
	$query = "SELECT * FROM provincia WHERE idprovincia = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe la Provincia."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idprovincia'].'",';
			$result .= '"descripcion":"'.$row['nombre'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putProvincia() {
	global $conn;

	$response = "";
	$descripcion = chequearCampo($_POST['descripcion']);
	
	$query = "INSERT INTO provincia (nombre) VALUES ('$descripcion')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"La provincia ha sido creada."}';
	}

	return $response;
}

function updateProvincia() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$descripcion = chequearCampo($_POST['descripcion']);

	$query = "UPDATE provincia SET nombre='$descripcion' WHERE idprovincia = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"La provincia ha sido actualizada."}';
	}

	return $response;
}

?>