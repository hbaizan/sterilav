<?php require_once('./db.php');

mysql_select_db($database_conn, $conn);

switch($_GET['op']) {
	case "listaVehiculos":
		echo listaVehiculos();
		break;
	case "getVehiculo":
		echo getVehiculo($_GET['id']);
		break;
	case "putVehiculo":
		echo putVehiculo();
		break;
	case "updateVehiculo":
		echo updateVehiculo();
		break;
}

function listaVehiculos() {
	global $conn;
	$query = "SELECT * FROM vehiculo";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Vehiculos."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idVehiculo'].'",';
			$result .= '"patente":"'.$row['patente'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getVehiculo($id) {
	global $conn;
	$query = "SELECT * FROM vehiculo WHERE idVehiculo = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Vehiculo."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idVehiculo'].'",';
			$result .= '"patente":"'.$row['patente'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putVehiculo() {
	global $conn;

	var response = "";
	$patente = chequearCampo($_POST['patente']);
	
	$query = "INSERT INTO vehiculo (patente) VALUES ('$patente')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"El Vehiculo ha sido creado."}';
	}

	return response;
}

function updateVehiculo() {
	global $conn;

	var response = "";
	$id = chequearCampo($_POST['id']);
	$cuit = chequearCampo($_POST['patente']);

	$query = "UPDATE vehiculo SET patente='$patente' WHERE idVehiculo = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"El Vehiculo ha sido actualizada."}';
	}

	return response;
}

function chequearCampo($campo) {
	if(!isset($campo) || $campo=="") {
		echo '{"status":"error","data":"El campo '.$campo.' no puede estar vacio"}';
		exit();
	}
	return $campo;
}
?>
