<?php

function listaDepositos() {
	global $conn, $tabla;
	$query = "SELECT * FROM deposito,empresa WHERE empresa_idempresa=idempresa";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['iddeposito'].'",';
		$result .= '"nombre":"'.$row['deposito_nombre'].'",';
		$result .= '"domicilio":"'.$row['deposito_domicilio'].'",';
		$result .= '"faltante":"'.$row['deposito_tiene_faltante'].'",';
		$result .= '"capita":"'.$row['deposito_capita'].'",';
		$result .= '"departamento":"'.$row['departamento_iddepartamento'].'",';
		$result .= '"idempresa":"'.$row['empresa_idempresa'].'",';
		$result .= '"empresa":"'.$row['empresa_razon_social'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
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
			$result .= '{"id":"'.$row['iddeposito'].'",';
			$result .= '"nombre":"'.$row['deposito_nombre'].'",';
			$result .= '"domicilio":"'.$row['deposito_domicilio'].'",';
			$result .= '"faltante":"'.$row['deposito_tiene_faltante'].'",';
			$result .= '"capita":"'.$row['deposito_capita'].'",';
			$result .= '"departamento":"'.$row['departamento_iddepartamento'].'",';
			$result .= '"empresa":"'.$row['empresa_idempresa'].'"';
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
	$faltante = chequearCampo($_POST['faltante']);
	$capita = chequearCampo($_POST['capita']);
	
	$query = "INSERT INTO deposito (deposito_nombre, deposito_domicilio, departamento_iddepartamento, empresa_idempresa, deposito_tiene_faltante, deposito_capita) VALUES ('$nombre','$domicilio', $departamento, $empresa, $faltante, $capita)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El deposito ha sido creado."}';
	}
}

function updateDeposito() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$nombre = chequearCampo($_POST['nombre']);
	$domicilio = chequearCampo($_POST['domicilio']);
	$departamento = chequearCampo($_POST['departamento']);
	$empresa = chequearCampo($_POST['empresa']);
	$faltante = chequearCampo($_POST['faltante']);
	$capita = chequearCampo($_POST['capita']);

	$query = "UPDATE deposito SET deposito_nombre='$nombre', departamento_iddepartamento=$departamento, deposito_domicilio='$domicilio', empresa_idempresa='$empresa', deposito_tiene_faltante=$faltante, deposito_capita=$capita WHERE iddeposito = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El deposito ha sido actualizado."}';
	}

	return $response;
}

?>