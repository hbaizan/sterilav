<?php

function getProximoRemito() {
	global $conn;

	$query = "SELECT MAX(idremito) AS idremito FROM remito";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";

	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"OK","data":"{"id":"1"}"}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.($row['idremito'] + 1).'"}}';
		}
	}
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
			$result .= '"observaciones":"'.$row['remito_observaciones'].'",';
			$chofer = json_decode(getChofer($row['chofer_idchofer']));
			$result .= '"chofer":'.json_encode($chofer->data).',';
			$vehiculo = json_decode(getVehiculo($row['vehiculo_idvehiculo']));
			$result .= '"vehiculo":'.json_encode($vehiculo->data).',';
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
	$chofer = $_POST['chofer'];
	$tipo = 1;
	$vehiculo = $_POST['vehiculo'];
	$fechaArray = explode("/", $_POST['fecha']);
	$fecha = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	$entrega = $_POST['horaEntrega'];
	$retiro = $_POST['horaRetiro'];
	$observaciones = $_POST['observaciones'];

	$query = "INSERT INTO remito (deposito_iddeposito, chofer_idchofer, remito_tipo_idremito_tipo, vehiculo_idvehiculo, ";
	$query .= "remito_fecha, remito_hora_entrega, remito_hora_retiro, remito_observaciones, persona_idpersona)";
	$query .= " VALUES ($deposito, $chofer, $tipo, $vehiculo, '$fecha', '$entrega', '$retiro', '$observaciones', $persona)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$id = mysql_insert_id();
		for($i=0; $i<count($_POST['lineaRemitos']); $i++) {
			$linea = $_POST['lineaRemitos'][$i];
			$query2 = "INSERT INTO remito_has_producto (remito_idremito, producto_idproducto, ingreso, egreso, cantidad_faltante) ";
			$query2 .= " VALUES (".$id.", ".$linea['codigo'].", ".$linea['entrega'].", ".$linea['retira'].", ".$linea['faltante'].")";
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

function listaRemitos($pagina) {
	global $conn, $tabla, $limite;

	$inicio = ($pagina-1) * $limite;
	$query = "SELECT * FROM remito, deposito, remito_tipo WHERE deposito_iddeposito=iddeposito AND remito_tipo_idremito_tipo=idremito_tipo ";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$recordcount = mysql_num_rows($recordset) or die(mysql_error());

	$query .= "ORDER BY idremito DESC LIMIT $inicio, $limite";

	$recordset = mysql_query($query, $conn) or die(mysql_error());

	$paginas = ceil($recordcount / $limite);
	$result = '{"status":"OK","paginas":"'.$paginas.'","data":[';
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

function updateRemito() {
	global $conn;

	//ID del remito original
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
			$chofer =  $_POST['chofer'];
			$vehiculo =  $_POST['vehiculo'];
			$fechaArray = explode("/", $_POST['fecha']);
			$fecha = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
			$entrega =  $row['remito_hora_entrega'];
			$retiro =  $row['remito_hora_retiro'];
			$observaciones =  $_POST['observaciones'];
			$persona =  $row['persona_idpersona'];
		}
	}

	if ($tipo == 2) {
		// orden de trabajo
		$queryrem = "INSERT INTO remito (persona_idpersona, deposito_iddeposito, chofer_idchofer, remito_tipo_idremito_tipo, vehiculo_idvehiculo, remito_fecha, remito_hora_entrega, remito_hora_retiro, remito_observaciones)"; 
		$queryrem .= "VALUES ($persona, $deposito, $chofer, $tipo, $vehiculo, '$fecha', '$entrega', '$retiro', '$observaciones')";  
		$resultrem = mysql_query($queryrem, $conn);
		$nuevoid = mysql_insert_id(); 

		//Si no se puede insertar el remito, termina        
		if(!$resultrem) {
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
				$faltante = (intval($row['ingreso']) - intval($linea['entrega'])) + intval($row['cantidad_faltante']);
			}
			$query2 = "INSERT INTO remito_has_producto (ingreso,egreso,cantidad_faltante,remito_idremito,producto_idproducto) VALUES (".$linea['entrega'].",".$linea['retira'].",".$faltante.",".$nuevoid.",".$linea['codigo'].")";
			$result2 = mysql_query($query2, $conn);
			if(!$result2) {
				$querydel = "DELETE FROM remito WHERE idremito=$nuevoid"; 
				$resultdel = mysql_query($querydel, $conn);
				$querydel = "DELETE FROM remito_has_producto WHERE remito_idremito=$nuevoid"; 
				$resultdel = mysql_query($querydel, $conn);
				$response = '{"status":"error","data":"'.mysql_error().'"}';
				break;
			} else {
				$response = '{"status":"OK","data":"La Orden de Trabajo ha sido creada."}';
			}
		}         
	}

elseif ($tipo == 3) {
        //Remito de Salida
        $queryrem = "INSERT INTO remito (persona_idpersona, deposito_iddeposito, chofer_idchofer, remito_tipo_idremito_tipo, vehiculo_idvehiculo, remito_fecha, remito_hora_entrega, remito_hora_retiro, remito_observaciones)";
		$queryrem .= " VALUES ($persona,$deposito,$chofer, $tipo,$vehiculo, '$fecha', '$entrega', '$retiro', '$observaciones')";
        $resultrem = mysql_query($queryrem, $conn);
        $nuevoid = mysql_insert_id();
        if(!$resultrem) {
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
                $faltante = (intval($row['ingreso']) - intval($linea['entrega'])) + intval($row['cantidad_faltante']);
            }
            $query2 = "INSERT INTO remito_has_producto (ingreso,egreso,cantidad_faltante,remito_idremito,producto_idproducto) VALUES (".$linea['entrega'].",".$linea['retira'].",".$faltante.",".$nuevoid.",".$linea['codigo'].")";
            $result2 = mysql_query($query2, $conn);
            if(!$result2) {
				$querydel = "DELETE FROM remito WHERE idremito=$nuevoid"; 
				$resultdel = mysql_query($querydel, $conn);
				$querydel = "DELETE FROM remito_has_producto WHERE remito_idremito=$nuevoid"; 
				$resultdel = mysql_query($querydel, $conn);
                $response = '{"status":"error","data":"'.mysql_error().'"}';
                break;
            } else {
                $response = '{"status":"OK","data":"El Remito de Salida ha sido creado."}';
            }
        }                 
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

function saveRemito() {
	global $conn;

	$id = $_POST['id'];
	$deposito = $_POST['deposito']['id'];
	$chofer =  $_POST['chofer'];
	$vehiculo =  $_POST['vehiculo'];
	$fechaArray = explode("/", $_POST['fecha']);
	$fecha = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	$entrega =  $_POST['horaEntrega'];
	$retiro =  $_POST['horaRetiro'];
	$observaciones =  $_POST['observaciones'];
	$persona =  $_POST['persona']['id'];

	$queryrem = "UPDATE remito SET persona_idpersona=$persona, deposito_iddeposito=$deposito, chofer_idchofer=$chofer, "; 
	$queryrem .= "vehiculo_idvehiculo=$vehiculo, remito_fecha='$fecha', remito_hora_entrega='$entrega', remito_hora_retiro='$retiro', remito_observaciones='$observaciones' ";
	$queryrem .= "WHERE idremito = $id";
	$resultrem = mysql_query($queryrem, $conn);

	//Si no se puede insertar el remito, termina        
	if(!$resultrem) {
		return '{"status":"error","data":"'.mysql_error().'"}';
		break;
	}
	$querydel = "DELETE FROM remito_has_producto WHERE remito_idremito=$id"; 
	$resultdel = mysql_query($querydel, $conn);
	for($i=0; $i<count($_POST['lineaRemitos']); $i++) {
		$linea = $_POST['lineaRemitos'][$i];
		if(!isset($linea['faltante']) || $linea['faltante']=="") {
			$faltante = 0;
		} else {
			$faltante = $linea['faltante'];
		}
		$query2 = "INSERT INTO remito_has_producto (ingreso,egreso,cantidad_faltante,remito_idremito,producto_idproducto) ";
		$query2 .= "VALUES (".$linea['entrega'].",".$linea['retira'].",".$faltante.",".$id.",".$linea['codigo'].")";
		$result2 = mysql_query($query2, $conn);
		if(!$result2) {
			$response = '{"status":"error","data":"'.mysql_error().' - '.$query2.'"}';
			break;
		} else {
			$response = '{"status":"OK","data":"El Remito ha sido guardado."}';
		}
	}

	return $response;
}	
?>