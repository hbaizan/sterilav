<?php

function listaIvas() {
	global $conn;
	$query = "SELECT * FROM iva";
	if(!$recordset = mysql_query($query, $conn)) {
		$result = '{"status":"error","data":"'.mysql_error().'"}';
	}
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idiva'].'",';
		$result .= '"descripcion":"'.$row['iva_condicion'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getIva($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM iva WHERE idiva = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Iva."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idiva'].'",';
			$result .= '"descripcion":"'.$row['iva_condicion'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}
?>