<?php

function listaProductos() {
	global $conn;
	$query = "SELECT * FROM producto,subgrupo WHERE subgrupo_idsubgrupo=idsubgrupo";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idproducto'].'",';
		$result .= '"descripcion":"'.$row['producto_nombre'].'",';
		$result .= '"idsubgrupo":"'.$row['subgrupo_idsubgrupo'].'",';
		$result .= '"subgrupo":"'.$row['subgrupo_nombre'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function listaProductosParaRemito() {
	global $conn;
	
	$query = "SELECT * FROM producto_has_deposito,producto,deposito WHERE ";
	$query .= "deposito_iddeposito=iddeposito AND producto_idproducto=idproducto";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '{"status":"OK","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		//Para cada producto, busco el ultimo remito de salida
		$queryrem = "SELECT MAX(idremito) AS idremito FROM remito WHERE remito_tipo_idremito_tipo=3 AND deposito_iddeposito=".$row['deposito_iddeposito'];
		$recordrem = mysql_query($queryrem, $conn) or die(mysql_error());
		$idremito = 0;
		$faltante = 0;
		if($rowrem = mysql_fetch_assoc($recordrem)) {
			$idremito = $rowrem['idremito'];
		}
		//Si hay remito de salida, busco el producto para ver si tiene faltanta
		if($idremito != 0) {
			$queryprod = "SELECT * FROM remito_has_producto WHERE producto_idproducto=".$row['producto_idproducto']." AND remito_idremito=$idremito";
			$recordprod = mysql_query($queryprod, $conn) or die(mysql_error());
			if($rowprod = mysql_fetch_assoc($recordprod)) {
				$faltante = $rowprod['cantidad_faltante'];
			}
		}
		if($faltante=='' || !isset($faltante)) {
			$faltante=0;
		}
		$result .= '{"codigo":"'.$row['producto_idproducto'].'",';
		$result .= '"deposito":"'.$row['deposito_iddeposito'].'",';
		$result .= '"descripcion":"'.$row['producto_nombre'].'",';
		$result .= '"faltante":'.$faltante.',';
		$result .= '"stock":'.$row['stock_inicial'].',';
		$result .= '"capita":'.$row['deposito_capita'];
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	return $result;
}

function listaProductosPorDeposito($iddeposito) {
	global $conn;
	
	$query = "SELECT * FROM producto_has_deposito,producto WHERE producto_idproducto=idproducto AND deposito_iddeposito=".$iddeposito;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"codigo":"'.$row['producto_idproducto'].'",';
		$result .= '"descripcion":"'.$row['producto_nombre'].'",';
		$result .= '"faltante":"'.$row['cantidad_tope'].'",';
		$result .= '"stock":"'.$row['stock_inicial'].'",';
		$result .= '"capita":"'.$row['deposito_capita'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']';
	
	return $result;
}

function listaProductosPorRemito($idremito) {
	global $conn;
	
	$query = "SELECT * FROM remito_has_producto,producto,producto_has_deposito WHERE remito_has_producto.producto_idproducto=idproducto AND producto_has_deposito.producto_idproducto=idproducto AND remito_idremito=".$idremito." GROUP BY idproducto";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$result = '[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"codigo":"'.$row['producto_idproducto'].'",';
		$result .= '"descripcion":"'.$row['producto_nombre'].'",';
		$result .= '"entrega":"'.($row['ingreso']+$row['cantidad_faltante']).'",';
		$result .= '"retira":"'.$row['egreso'].'",';
		$result .= '"faltante":"'.$row['cantidad_faltante'].'"';
		$result .= '},';
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']';
	
	return $result;
}

function getProducto($id) {
	global $conn;
	$query = "SELECT * FROM producto,subgrupo WHERE subgrupo_idsubgrupo=idsubgrupo AND idproducto = ".$id;
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	$result = "";
	
	if(mysql_num_rows($recordset)==0) {
		$result = '{"status":"error","data":"No existe el Producto."}';
	} else {
		$result = '{"status":"OK","data":';
		if($row = mysql_fetch_assoc($recordset)) {
			$result .= '{"id":"'.$row['idproducto'].'",';
			$result .= '"descripcion":"'.$row['producto_nombre'].'",';
			$result .= '"grupo":"'.$row['grupo_idgrupo'].'",';
			$result .= '"subgrupo":"'.$row['subgrupo_idsubgrupo'].'"';
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
	$subgrupo = chequearCampo($_POST['subgrupo']);
	$query = "INSERT INTO producto (producto_nombre, subgrupo_idsubgrupo) VALUES ('$descripcion',$subgrupo)";
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
	$subgrupo = chequearCampo($_POST['subgrupo']);
	$query = "UPDATE producto SET producto_nombre='$descripcion', subgrupo_idsubgrupo=$subgrupo WHERE idproducto = ".$id;
	$result = mysql_query($query, $conn);
	if(!$result) {
		return '{"status":"error","data":"'.mysql_error().'"}';
	} else {
		return '{"status":"OK","data":"El Producto ha sido creado."}';
	}
}
?>
