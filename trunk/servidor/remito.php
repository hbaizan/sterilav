<?php

function listaRemitos() {
	global $conn, $tabla;
	$query = "SELECT * FROM remito, deposito WHERE deposito_iddeposito=iddeposito";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idremito'].'",';
		$result .= '"fecha":"'.$row['remito_fecha'].'",';
		$result .= '"iddeposito":"'.$row['deposito_iddeposito'].'",';
		$result .= '"deposito":"'.$row['deposito_nombre'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

/*function getDeposito($id) {
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
}*/

function putRemito() {
	global $conn;

	$response = "";
	
	$deposito = $_POST['deposito']['id'];
	$chofer = 1;
	$tipo = 1;
	$vehiculo = 1;
	$fecha = $_POST['fecha'];
	$entrega = 0;
	$retiro = 0;
	$observaciones = "";
	
	$query = "INSERT INTO remito (deposito_iddeposito, chofer_idchofer, remito_tipo_idremito_tipo, vehiculo_idvehiculo, ";
	$query .= "remito_fecha, remito_hora_entrega, remito_hora_retiro, remito_observaciones)";
	$query .= " VALUES ($deposito, $chofer, $tipo, $vehiculo, '$fecha', $entrega, $retiro, '$observaciones')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$id = mysql_insert_id();
		for($i=0; $i<count($_POST['lineaRemitos']); $i++) {
			$linea = $_POST['lineaRemitos'][$i];
			$query2 = "INSERT INTO remito_has_producto (remito_idremito, producto_idproducto, cantidad_real) ";
			$query2 .= " VALUES (".$id.", ".$linea['codigo'].", ".$linea['cantidad'].")";
			$result2 = mysql_query($query2, $conn);
			if(!$result2) {
				$response = '{"status":"error","data":"'.mysql_error().'"}';
				break;
			} else {
				$response = '{"status":"OK","data":"El remito ha sido creado."}';
			}
		}
	}
	
	return $response;
}

function updateRemito() {
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
	return '{"status":"error","data":"operacion no soportada"}';
}

?>