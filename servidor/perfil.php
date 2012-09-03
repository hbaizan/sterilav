<?php

function listaPerfiles() {
	global $conn;
	$query = "SELECT * FROM perfil";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No hay Perfiles."}';
	} else {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idperfil'].'",';
			$result .= '"descripcion":"'.$row['perfil_nombre'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= ']}';
	}
	
	return $result;
}

function listaPermisos($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM grupopantalla";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	if(mysql_num_rows($recordset)>0) {
		$result = '{"status":"OK","data":[';
		while($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"grupo":"'.$row['nombre'].'","aplicaciones":[';
			$query2 = "SELECT * FROM permiso,aplicacion WHERE aplicacion_idaplicacion=idaplicacion AND perfil_idperfil = ".$id. " AND grupo_idgrupopantalla = ".$row['idgrupopantalla'];
			$recordset2 = mysql_query($query2, $conn) or die(mysql_error());
			while($row2 = mysql_fetch_assoc($recordset2)) {
				$result .= '{"id":"'.$row2['aplicacion_idaplicacion'].'",';
				$result .= '"pantalla":"'.$row2['descripcion'].'",';
				$result .= '"aplicacion":"'.$row2['link_aplicacion'].'"';
				$result .= '},';
			}
			if(mysql_num_rows($recordset2)>0) {
				$result = substr($result, 0, strlen($result)-1);
			}
			$result .= ']},';
		}
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
			
	return $result;
}

function getPerfil($id) {
	global $conn, $tabla;
	$query = "SELECT * FROM perfil WHERE idperfil = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Perfil."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idperfil'].'",';
			$result .= '"descripcion":"'.$row['perfil_nombre'].'"';
			$result .= '},';
		}
		$result = substr($result, 0, strlen($result)-1);
		$result .= '}';
	}
	
	return $result;
}
?>