<?php require_once('./db.php');

mysql_select_db($database_conn, $conn);

switch($_GET['op']) {
	case "listaChoferes":
		echo listaChoferes();
		break;
	case "getChofer":
		echo getChofer($_GET['id']);
		break;
	case "putChofer":
		echo putChofer();
		break;
	case "updateChofer":
		echo updateChofer();
		break;
}

function listaChoferes() {
	global $conn;
	$query = "SELECT * FROM chofer";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Choferes."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idChofer'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"apellido":"'.$row['apellido'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getChofer($id) {
	global $conn;
	$query = "SELECT * FROM chofer WHERE idChofer = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Chofer."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idChofer'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"apellido":"'.$row['apellido'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putChofer() {
	global $conn;

	var response = "";
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);
	
	$query = "INSERT INTO chofer (nombre,apellido) VALUES ('$nombre','$apellido')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"El chofer ha sido creado."}';
	}

	return response;
}

function updateChofer() {
	global $conn;

	var response = "";
	$id = chequearCampo($_POST['id']);
	$nombre = chequearCampo($_POST['nombre']);
	$apellido = chequearCampo($_POST['apellido']);

	$query = "UPDATE chofer SET nombre='$nombre',apellido='$apellido' WHERE idChofer = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"El chofer ha sido actualizado."}';
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
