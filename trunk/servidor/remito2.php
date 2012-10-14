<?php

function listaRemitos() {
	global $conn, $tabla;
	$query = "SELECT * FROM remito, deposito, remito_tipo WHERE deposito_iddeposito=iddeposito AND remito_tipo_idremito_tipo=idremito_tipo";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idremito'].'",';
		$result .= '"idtipo":"'.$row['remito_tipo_idremito_tipo'].'",';
		$result .= '"fecha":"'.$row['remito_fecha'].'",';
		$result .= '"iddeposito":"'.$row['deposito_iddeposito'].'",';
		$result .= '"deposito":"'.$row['deposito_nombre'].'",';
		$result .= '"tipo":"'.$row['remito_tipo_nombre'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getRemito($id) {
	global $conn;
	$query = "SELECT * FROM remito,deposito,empresa WHERE deposito_iddeposito=iddeposito AND empresa_idempresa=idempresa AND idRemito = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Remito."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idremito'].'",';
			$result .= '"tipo":"'.$row['remito_tipo_idremito_tipo'].'",';
			$deposito = json_decode(getDeposito($row['deposito_iddeposito']));
			$result .= '"deposito":'.json_encode($deposito->data).',';
			$empresa = json_decode(getEmpresa($row['empresa_idempresa']));
			$result .= '"empresa":'.json_encode($empresa->data).',';
			$persona = json_decode(getUsuario($row['persona_idpersona']));
			$result .= '"persona":'.json_encode($persona->data).',';
			$result .= '"fecha":"'.$row['remito_fecha'].'",';
			$result .= '"horaEntrega":"'.$row['remito_hora_entrega'].'",';
			$result .= '"horaRetiro":"'.$row['remito_hora_retiro'].'",';
			$result .= '"productos":'.listaProductosPorRemito($row['idremito']).'';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putRemito() {
	global $conn;

	$response = "";
	
	$persona = $_POST['persona']['id'];
	$deposito = $_POST['deposito']['id'];
	$chofer = $_POST['chofer']['id'];
	$tipo = 1;
	$vehiculo = 1;
	$fecha = $_POST['fecha'];
	$entrega = 0;
	$retiro = 0;
	$observaciones = "";
	
	$query = "INSERT INTO remito (deposito_iddeposito, chofer_idchofer, remito_tipo_idremito_tipo, vehiculo_idvehiculo, ";
	$query .= "remito_fecha, remito_hora_entrega, remito_hora_retiro, remito_observaciones, persona_idpersona)";
	$query .= " VALUES ($deposito, $chofer, $tipo, $vehiculo, '$fecha', $entrega, $retiro, '$observaciones', $persona)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$id = mysql_insert_id();
		for($i=0; $i<count($_POST['lineaRemitos']); $i++) {
			$linea = $_POST['lineaRemitos'][$i];
			$query2 = "INSERT INTO remito_has_producto (remito_idremito, producto_idproducto, cantidad_real) ";
			$query2 .= " VALUES (".$id.", ".$linea['codigo'].", ".$linea['entrega'].")";
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

	$id = $_POST['id'];
	$tipo = $_POST['tipo'];
	//Busco el remito, si existe, copio el resto de los datos en el nuevo remito
	$query = "SELECT * FROM remito WHERE idremito = $id";
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
		break;
	} else {
		if($row = mysql_fetch_assoc($result)) {
			$deposito = $row['deposito_iddeposito'];
			$chofer =  $row['chofer_idchofer'];
			$vehiculo =  $row['vehiculo_idvehiculo'];
			$fecha =  $row['remito_fecha'];
			$entrega =  $row['remito_hora_entrega'];
			$retiro =  $row['remito_hora_retiro'];
			$observaciones =  $row['remito_observaciones'];
			$persona =  $row['persona_idpersona'];
		}
	}
	
	if($tipo == 2) {
		//Orden de Trabajo
		$query = "INSERT INTO remito (deposito_iddeposito, chofer_idchofer, remito_tipo_idremito_tipo, vehiculo_idvehiculo, ";
		$query .= "remito_fecha, remito_hora_entrega, remito_hora_retiro, remito_observaciones, persona_idpersona)";
		$query .= " VALUES ($deposito, $chofer, $tipo, $vehiculo, '$fecha', $entrega, $retiro, '$observaciones', $persona)";
		$result = mysql_query($query, $conn);
		$nuevoid = mysql_insert_id();
		if(!$result) {
			return '{"status":"error","data":"'.mysql_error().'"}';
			break;
		}
	
		for($i=0; $i<count($_POST['lineaRemitos']); $i++) {
			$linea = $_POST['lineaRemitos'][$i];
			$query = "SELECT * FROM remito_has_producto WHERE remito_idremito=".$id." AND producto_idproducto=".$linea['codigo'];
			$result = mysql_query($query, $conn);
			if(!$result) {
				$faltante = 0;
			} else {
				$row = mysql_fetch_assoc($result);
				$faltante = (intval($row['cantidad_real']) - intval($linea['entrega'])) + intval($row['cantidad_faltante']);
			}
			$query2 = "INSERT INTO remito_has_producto (cantidad_real,cantidad_faltante,remito_idremito,producto_idproducto) VALUES (".$linea['entrega'].",".$faltante.",".$nuevoid.",".$linea['codigo'].")";
			$result2 = mysql_query($query2, $conn);
			if(!$result2) {
				$response = '{"status":"error","data":"'.mysql_error().'"}';
				break;
			} else {
				$response = '{"status":"OK","data":"El Remito ha sido actualizado."}';
			}
		}
	} elseif ($tipo == 3) {
		//Remito de Salida
		return '{"status":"OK","data":"Operacion no soportada."}';
		break;
	} elseif ($tipo == 4) {
		//Ajuste
		return '{"status":"OK","data":"Operacion no soportada."}';
		break;
	} elseif ($tipo == 5) {
		//Recomposicion
		return '{"status":"OK","data":"Operacion no soportada."}';
		break;
	} else {
		return '{"status":"error","data":"Error en datos enviados."}';
		break;
	}
	return $response;
}

=======
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
  
  //información para el remito
  $nombre = chequearCampo($_POST['nombre']);
	$domicilio = chequearCampo($_POST['domicilio']);
	$departamento = chequearCampo($_POST['departamento']);
	$empresa = chequearCampo($_POST['empresa']);
	$faltante = chequearCampo($_POST['faltante']);
	$capita = chequearCampo($_POST['capita']);
  
  //información para remito TENGO QUE CARGAR LOS NOMBRES DE LOS CAMPOS QUE ESTAN EN REMITO.HTML DEL LADO DEL CLIENTE
  $idpersona = chequearCampo($_POST['nombre']);
	$iddeposito = chequearCampo($_POST['domicilio']);
	$idchofer = chequearCampo($_POST['departamento']);
	$idremitotipo = chequearCampo($_POST['empresa']);
	$idvehiculo = chequearCampo($_POST['faltante']);
	$rfecha = chequearCampo($_POST['capita']); 
  $rhoraentrega = chequearCampo($_POST['capita']); 
  $rhoraretiro = chequearCampo($_POST['capita']); 
  $robservaciones = chequearCampo($_POST['capita']); 
  
  //update de remito_has_producto
  $idremito = chequearCampo($_POST['nombre']);
	$idproducto = chequearCampo($_POST['domicilio']);
	$cantidadreal = chequearCampo($_POST['departamento']);
	$cantidadfaltante = chequearCampo($_POST['empresa']);  
  
  // actualización de deposito  
	$query = "UPDATE deposito SET deposito_nombre='$nombre', departamento_iddepartamento=$departamento, deposito_domicilio='$domicilio', empresa_idempresa='$empresa', deposito_tiene_faltante=$faltante, deposito_capita=$capita WHERE iddeposito = ".$id;
	$result = mysql_query($query, $conn);
    
   //actualizacion del remito 
  $queryrem = "UPDATE remito SET persona_idpersona=$idpersona, deposito_iddeposito=$iddeposito, chofer_idchofer=$idchofer, remito_tipo_idremito_tipo=$iremitotipo, vehiculo_idvehiculo=$idvehiculo, remito_fecha=$rfecha , remito_hora_entrega=$rhoraentrega, remito_hora_retiro=$rhoraretiro WHERE iddeposito = deposito_iddeposito";   
  $resultrem = mysql_query($queryrem, $conn); 
   
    //actualizacion del remito_has_producto rhp
  $queryrhp = "UPDATE remito_has_producto SET remito_idremito=$idremito, producto_idproducto=$idproducto, cantidad_real=$cantidadreal, cantidad_faltante=$cantidadfaltante WHERE idremito = idremito";   
  $resultrhp = mysql_query($queryrhp, $conn); 



   if(!$result and !$resultrem and !$resultrhp ) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El remito ha sido actualizado."}';
	}

	return $response;
	return '{"status":"error","data":"operacion no soportada"}';
  }

>>>>>>> .r46
?>