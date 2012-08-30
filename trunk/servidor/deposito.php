<?php require_once('./db.php');

mysql_select_db($database_conn, $conn);

switch($_GET['op']) {
	case "listaDepositos":
		echo listaDepositos();
		break;
	case "getDeposito":
		echo getDeposito($_GET['id']);
		break;
	case "putDeposito":
		echo putDeposito();
		break;
	case "updateDeposito":
		echo updateDeposito();
		break;
}

function listaDepositos() {
	global $conn, $tabla;
	$query = "SELECT * FROM deposito";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Depositos."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idDeposito'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"domicilio":"'.$row['domicilio'].'",';
			$result .= '"departamento":"'.$row['departamento'].'",';
			$result .= '"empresa":"'.$row['empresa'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getDeposito($id) {
	global $conn;
	$query = "SELECT * FROM deposito WHERE idDeposito = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Deposito."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idDeposito'].'",';
			$result .= '"nombre":"'.$row['nombre'].'",';
			$result .= '"domicilio":"'.$row['domicilio'].'",';
			$result .= '"departamento":"'.$row['departamento'].'",';
			$result .= '"empresa":"'.$row['empresa'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putDeposito() {
	global $conn;

	$nombre = chequearCampo($_POST['nombre']);
	$domicilio = chequearCampo($_POST['domicilio']);
	$departamento = chequearCampo($_POST['departamento']);
	$empresa = chequearCampo($_POST['empresa']);
	
	$query = "INSERT INTO deposito (nombre, domicilio, departamento, empresa) VALUES ('$nombre','$domicilio', $departamento, $empresa)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El deposito ha sido creado."}';
	}
}

function updateDeposito() {
	global $conn;

	var response = "";
	$id = chequearCampo($_POST['id']);
	$nombre = chequearCampo($_POST['nombre']);
	$domicilio = chequearCampo($_POST['domicilio']);
	$departamento = chequearCampo($_POST['departamento']);
	$empresa = chequearCampo($_POST['empresa']);

	$query = "UPDATE deposito SET nombre='$nombre', departamento=$departamento, domicilio='$domicilio', empresa='$empresa' WHERE idDeposito = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"El deposito ha sido actualizado."}';
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
