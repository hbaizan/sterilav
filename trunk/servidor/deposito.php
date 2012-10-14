<?php

function listaDepositos() {
	global $conn;
	$query = "SELECT * FROM deposito,empresa WHERE empresa_idempresa=idempresa AND NOT deposito_inactivo=2";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['iddeposito'].'",';
		$result .= '"nombre":"'.$row['deposito_nombre'].'",';
		$result .= '"domicilio":"'.$row['deposito_domicilio'].'",';
		$result .= '"departamento":"'.$row['departamento_iddepartamento'].'",';
		$result .= '"idempresa":"'.$row['empresa_idempresa'].'",';
		$result .= '"empresa":"'.$row['empresa_razon_social'].'",';
		$result .= '"activo":"'.$row['deposito_inactivo'].'",';
		$remito = json_decode(depositoTieneRemito($row['iddeposito']));
		if($remito->data) {
			$result .= '"remito":true';
		} else {
			$result .= '"remito":false';
		}		
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function listaDepositosPorEmpresa($idempresa) {
	global $conn;
	$query = "SELECT * FROM deposito,departamento,empresa WHERE empresa_idempresa=idempresa AND departamento_iddepartamento=iddepartamento AND deposito_inactivo=0 AND empresa_idempresa=".$idempresa;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['iddeposito'].'",';
		$result .= '"nombre":"'.$row['deposito_nombre'].'",';
		$result .= '"domicilio":"'.$row['deposito_domicilio'].'",';
		$result .= '"iddepartamento":"'.$row['departamento_iddepartamento'].'",';
		$result .= '"departamento":"'.$row['departamento_nombre'].'",';
		$result .= '"idempresa":"'.$row['empresa_idempresa'].'",';
		$result .= '"empresa":"'.$row['empresa_razon_social'].'",';
		$result .= '"activo":"'.$row['deposito_inactivo'].'",';
		$remito = json_decode(depositoTieneRemito($row['iddeposito']));
		if($remito->data) {
			$result .= '"remito":true';
		} else {
			$result .= '"remito":false';
		}		
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']';
	
	return $result;
}

function getDeposito($id) {
	global $conn;
	$query = "SELECT * FROM deposito,departamento WHERE iddepartamento=departamento_iddepartamento AND NOT deposito_inactivo=2 AND idDeposito = ".$id;
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
			$result .= '"iddepartamento":"'.$row['departamento_iddepartamento'].'",';
			$result .= '"departamento":"'.$row['departamento_nombre'].'",';
			$result .= '"empresa":"'.$row['empresa_idempresa'].'",';
			$result .= '"activo":"'.$row['deposito_inactivo'].'",';
			$result .= '"productos":'.listaProductosPorDeposito($row['iddeposito']).',';
			$remito = json_decode(depositoTieneRemito($row['iddeposito']));
			if($remito->data) {
				$result .= '"remito":true';
			} else {
				$result .= '"remito":false';
			}		
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
	if(!isset($_POST['productos']) || count($_POST['productos'])==0) {
		return '{"status":"error","data":"El deposito debe tener al menos un producto asociado"}';			
	}
	$productos = chequearCampo($_POST['productos']);
	
	$query = "INSERT INTO deposito (deposito_nombre, deposito_domicilio, departamento_iddepartamento, empresa_idempresa) VALUES ('$nombre','$domicilio', $departamento, $empresa)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$id = mysql_insert_id();
		for($i=0; $i<count($_POST['productos']); $i++) {
			$linea = $_POST['productos'][$i];
			$query2 = "INSERT INTO producto_has_deposito (producto_idproducto, deposito_iddeposito, cantidad_tope, deposito_capita, stock_inicial)";
			$query2 .= "VALUES (".$linea["codigo"].", $id, ".$linea["faltante"].", ".$linea["capita"].", ".$linea["stock"].")";
			$result2 = mysql_query($query2, $conn);
			if(!$result2) {
				return '{"status":"error","data":"'.mysql_error().'"}';
			}
		}
		return '{"status":"OK","data":"El Deposito ha sido creado."}';
	}
}

function updateDeposito() {
	global $conn;

	$id = chequearCampo($_POST['id']);
	$nombre = chequearCampo($_POST['nombre']);
	$domicilio = chequearCampo($_POST['domicilio']);
	$departamento = chequearCampo($_POST['departamento']);
	$empresa = chequearCampo($_POST['empresa']);
	$inactivo = $_POST['activo'];

	$query = "UPDATE deposito SET deposito_nombre='$nombre', departamento_iddepartamento=$departamento, deposito_domicilio='$domicilio', empresa_idempresa='$empresa', deposito_inactivo=$inactivo WHERE iddeposito = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$query2 = "DELETE FROM producto_has_deposito WHERE deposito_iddeposito=$id";
		$result2 = mysql_query($query2, $conn);
		if(!$result2) {
			return '{"status":"error","data":"'.mysql_error().'"}';
		}		
		for($i=0; $i<count($_POST['productos']); $i++) {
			$linea = $_POST['productos'][$i];
			$query2 = "INSERT INTO producto_has_deposito (producto_idproducto, deposito_iddeposito, cantidad_tope, deposito_capita, stock_inicial)";
			$query2 .= "VALUES (".$linea["codigo"].", $id, ".$linea["faltante"].", ".$linea["capita"].", ".$linea["stock"].")";
			$result2 = mysql_query($query2, $conn);
			if(!$result2) {
				return '{"status":"error","data":"'.mysql_error().'"}';
			}
		}
		return '{"status":"OK","data":"El Deposito ha sido actualizado."}';
	}
}

function borrarDeposito($id) {
	global $conn;

	$remito = json_decode(depositoTieneRemito($id));
	if($remito->data) {
		return '{"status":"error","data":"El Deposito tiene remitos asociados y no puede ser eliminado."}';
		exit;
	}
	$query = "UPDATE deposito SET deposito_inactivo=2 WHERE iddeposito = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El Deposito ha sido eliminado."}';
	}
}

function depositoTieneRemito($id) {
	global $conn;
	
	$query = "SELECT * FROM remito WHERE deposito_iddeposito=".$id;
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

?>