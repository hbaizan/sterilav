<?php

function listaProductos() {
	global $conn;
	$query = "SELECT * FROM producto";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idProducto'].'",';
		$result .= '"descripcion":"'.$row['descripcion'].'",';
		$result .= '"estado":"'.$row['estado'].'",';
		$result .= '"cantidad":"'.$row['cantidad'].'",';
		$result .= '"grupo":"'.$row['idGrupo'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function getProducto($id) {
	global $conn;
	$query = "SELECT * FROM producto WHERE idProducto = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Producto."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idProducto'].'",';
			$result .= '"descripcion":"'.$row['descripcion'].'",';
			$result .= '"estado":"'.$row['estado'].'",';
			$result .= '"cantidad":"'.$row['cantidad'].'",';
			$result .= '"grupo":"'.$row['idGrupo'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}

function putProducto() {
	global $conn;
	$descripcion = chequearCampo($_POST['descripcion']);
	$estado = chequearCampo($_POST['estado']);
	$cantidad = chequearCampo($_POST['cantidad']);
	$grupo = chequearCampo($_POST['grupo']);
	$query = "INSERT INTO producto (descripcion, estado, cantidad, idGrupo) VALUES ('$descripcion', '$estado',$cantidad,$grupo)";
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El Producto ha sido creado."}';
	}
}

function updateProducto() {
	global $conn;
	$id = chequearCampo($_POST['id']);
	$descripcion = chequearCampo($_POST['descripcion']);
	$estado = chequearCampo($_POST['estado']);
	$cantidad = chequearCampo($_POST['cantidad']);
	$grupo = chequearCampo($_POST['grupo']);
	$query = "UPDATE producto SET descripcion='$descripcion', estado='$estado', cantidad=$cantidad, idGrupo=$grupo WHERE idProducto = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El Producto ha sido creado."}';
	}
}
?>