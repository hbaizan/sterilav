<?php

function listaClientes() {
	global $conn;
	$query = "SELECT * FROM cliente";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"result":"NO","data":"No hay Clientes."}';
	} else {
		$result = '{"result":'.mysql_num_rows($recordset).',"data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['id'].'",';
			$result .= '"nombre":"'.$row['ee'].'",';
			$result .= '"apellido":"'.$row['tt'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function getCliente($id) {
	global $conn;
	$query = "SELECT * FROM cliente WHERE id = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"result":"NO","data":"No hay Clientes."}';
	} else {
		$result = '{"result":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['id'].'",';
			$result .= '"nombre":"'.$row['ee'].'",';
			$result .= '"apellido":"'.$row['tt'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putCliente($cliente) {
	echo '{"result":"OK","data":"No esta implementado."}';
}
?>