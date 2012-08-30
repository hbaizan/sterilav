<?php require_once('./db.php');

mysql_select_db($database_conn, $conn);

switch($_GET['op']) {
	case "listaProvincias":
		echo listaProvincias();
		break;
	case "getProvincia":
		echo getProvincia($_GET['id']);
		break;
	case "putProvincia":
		echo putProvincia();
		break;
	case "updateProvincia":
		echo updateProvincia();
		break;
}

function listaProvincias() {
	global $conn;
	$query = "SELECT * FROM provincia";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Provincias."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idProvincia'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getProvincia($id) {
	global $conn;
	$query = "SELECT * FROM provincia WHERE idProvincia = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe la Provincia."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idProvincia'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putProvincia() {
	global $conn;

	var response = "";
	$descripcion = chequearCampo($_POST['descripcion']);
	
	$query = "INSERT INTO provincia (descripcion) VALUES ('$descripcion')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"La provincia ha sido creada."}';
	}

	return response;
}

function updateProvincia() {
	global $conn;

	var response = "";
	$id = chequearCampo($_POST['id']);
	$descripcion = chequearCampo($_POST['descripcion']);

	$query = "UPDATE provincia SET descripcion='$descripcion' WHERE idProvincia = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"La provincia ha sido actualizada."}';
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
