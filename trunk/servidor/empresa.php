<?php

function listaEmpresas() {
	global $conn;
	$query = "SELECT * FROM empresa";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		//$query2 = "SELECT * FROM deposito WHERE empresa_idempresa = ".$recordset['idempresa'];
		//$recordset2 = mysql_query($query2, $conn) or die(mysql_error());

		$result .= '{"id":"'.$row['idempresa'].'",';
		$result .= '"razonSocial":"'.$row['empresa_razon_social'].'",';
		$result .= '"cuit":"'.$row['empresa_cuit'].'",';
		$result .= '"depositos":'.listaDepositosPorEmpresa($row['idempresa']).'';
		
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getEmpresa($id) {
	global $conn;
	$query = "SELECT * FROM empresa WHERE idempresa = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe la Empresa."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idempresa'].'",';
			$result .= '"razonSocial":"'.$row['empresa_razon_social'].'",';
			$result .= '"cuit":"'.$row['empresa_cuit'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putEmpresa() {
	global $conn;

	$response = "";
	$razonSocial = chequearCampo($_POST['razonSocial']);
	$cuit = chequearCampo($_POST['cuit']);
	
	$query = "INSERT INTO empresa (empresa_razon_social,empresa_cuit) VALUES ('$razonSocial','$cuit')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"La empresa ha sido creada."}';
	}

	return $response;
}

function updateEmpresa() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$razonSocial = chequearCampo($_POST['razonSocial']);
	$cuit = chequearCampo($_POST['cuit']);

	$query = "UPDATE empresa SET empresa_razon_social='$razonSocial',empresa_cuit='$cuit' WHERE idempresa = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"La empresa ha sido actualizada."}';
	}

	return $response;
}

?>