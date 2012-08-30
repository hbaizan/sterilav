<?php require_once('./db.php');

mysql_select_db($database_conn, $conn);

switch($_GET['op']) {
	case "listaEmpresas":
		echo listaEmpresas();
		break;
	case "getEmpresa":
		echo getEmpresa($_GET['id']);
		break;
	case "putEmpresa":
		echo putEmpresa();
		break;
	case "updateEmpresa":
		echo updateEmpresa();
		break;
}

function listaEmpresas() {
	global $conn;
	$query = "SELECT * FROM empresa";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Empresas."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idEmpresa'].'",';
			$result .= '"razonSocial":"'.$row['razonSocial'].'",';
			$result .= '"cuit":"'.$row['cuit'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getEmpresa($id) {
	global $conn;
	$query = "SELECT * FROM empresa WHERE idEmpresa = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe la Empresa."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idEmpresa'].'",';
			$result .= '"razonSocial":"'.$row['razonSocial'].'",';
			$result .= '"cuit":"'.$row['cuit'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putEmpresa() {
	global $conn;

	var response = "";
	$razonSocial = chequearCampo($_POST['razonSocial']);
	$cuit = chequearCampo($_POST['cuit']);
	
	$query = "INSERT INTO empresa (razonSocial,cuit) VALUES ('$razonSocial','$cuit')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"La empresa ha sido creada."}';
	}

	return response;
}

function updateEmpresa() {
	global $conn;

	var response = "";
	$id = chequearCampo($_POST['id']);
	$razonSocial = chequearCampo($_POST['razonSocial']);
	$cuit = chequearCampo($_POST['cuit']);

	$query = "UPDATE empresa SET razonSocial='$razonSocial',cuit='$cuit' WHERE idEmpresa = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		response = '{"status":"OK","data":"La empresa ha sido actualizada."}';
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
