<?php

function listaChoferes() {
	global $conn;
	$query = "SELECT * FROM chofer WHERE NOT chofer_inactivo = 2";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idchofer'].'",';
		$result .= '"nombre":"'.$row['chofer_nombre'].'",';
		$result .= '"apellido":"'.$row['chofer_apellido'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getChofer($id) {
	global $conn;
	$query = "SELECT * FROM chofer WHERE idchofer = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Chofer."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idchofer'].'",';
			$result .= '"nombre":"'.$row['chofer_nombre'].'",';
			$result .= '"apellido":"'.$row['chofer_apellido'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putChofer() {
	global $conn;

	$response = "";
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);
	
	$query = "INSERT INTO chofer (chofer_nombre,chofer_apellido) VALUES ('$nombre','$apellido')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El chofer ha sido creado."}';
	}

	return $response;
}

function updateChofer() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);

	$query = "UPDATE chofer SET chofer_nombre='$nombre',chofer_apellido='$apellido' WHERE idchofer = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El chofer ha sido actualizado."}';
	}

	return $response;
}

function borrarChofer($id) {
	global $conn;

	if(choferTieneRemito2($id)) {
		return '{"status":"error","data":"El Chofer tiene remitos asociados y no puede ser eliminado."}';
		exit;
	}
	$query = "UPDATE chofer SET chofer_inactivo=2 WHERE idchofer = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El Chofer ha sido eliminado."}';
	}
}

function choferTieneRemito($id) {
	global $conn;
	
	$query = "SELECT * FROM remito WHERE chofer_idchofer=".$id;
	$recordset = mysql_query($query, $conn);
	if(!$recordset) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		if(mysql_num_rows($recordset)==0) {
			return '{"status":"OK","data":false}';
		} else {
			return '{"status":"OK","data":true}';
		}
	}
}

function choferTieneRemito2($id) {
	global $conn;
	
	$query = "SELECT * FROM remito WHERE chofer_idchofer=".$id;
	$recordset = mysql_query($query, $conn);
	if(!$recordset) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		if(mysql_num_rows($recordset)==0) {
			return false;
		} else {
			return true;
		}
	}
}

?>