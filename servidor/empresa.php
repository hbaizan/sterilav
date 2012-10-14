<?php

function listaEmpresas() {
	global $conn;
	$query = "SELECT * FROM empresa, iva WHERE iva_idiva=idiva";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idempresa'].'",';
		$result .= '"razonSocial":"'.$row['empresa_razon_social'].'",';
		$result .= '"cuit":"'.$row['empresa_cuit'].'",';
		$result .= '"depositos":'.listaDepositosPorEmpresa($row['idempresa']).',';
		$result .= '"idiva":"'.$row['iva_idiva'].'",';
		$result .= '"iva":"'.$row['iva_condicion'].'"';
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
	$query = "SELECT * FROM empresa,iva WHERE iva_idiva=idiva AND idempresa = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe la Empresa."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idempresa'].'",';
			$result .= '"razonSocial":"'.$row['empresa_razon_social'].'",';
			$result .= '"cuit":"'.$row['empresa_cuit'].'",';
			$result .= '"depositos":'.listaDepositosPorEmpresa($row['idempresa']).',';
			$result .= '"idiva":"'.$row['iva_idiva'].'",';			
			$result .= '"iva":"'.$row['iva_condicion'].'"';			
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
	$iva = chequearCampo($_POST['iva']);
	
	$query = "INSERT INTO empresa (empresa_razon_social,empresa_cuit,iva_idiva) VALUES ('$razonSocial','$cuit',$iva)";
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
	$iva = chequearCampo($_POST['iva']);

	$query = "UPDATE empresa SET empresa_razon_social='$razonSocial',empresa_cuit='$cuit',iva_idiva=$iva WHERE idempresa = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"La empresa ha sido actualizada."}';
	}

	return $response;
}

?>