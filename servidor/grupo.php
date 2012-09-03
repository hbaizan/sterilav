<?php

function listaGrupos() {
	global $conn;
	$query = "SELECT * FROM grupo";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idgrupo'].'",';
		$result .= '"descripcion":"'.$row['grupo_nombre'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getGrupo($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM grupo WHERE idgrupo = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Grupo."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idgrupo'].'",';
			$result .= '"descripcion":"'.$row['grupo_nombre'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putGrupo() {
	global $conn;

	$response = "";
	$descripcion = chequearCampo($_POST['descripcion']);
	
	$query = "INSERT INTO grupo (grupo_nombre) VALUES ('$descripcion')";
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El grupo ha sido creado."}';
	}

	return $response;
}

function updateGrupo() {
	global $conn;

	$response = "";
	$id = chequearCampo($_POST['id']);
	$descripcion = chequearCampo($_POST['descripcion']);

	$query = "UPDATE grupo SET grupo_nombre='$descripcion' WHERE idgrupo = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		$response = '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		$response = '{"status":"OK","data":"El grupo ha sido actualizado."}';
	}

	return $response;
}
?>