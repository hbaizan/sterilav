<?php
$reporteCSV = "";

function reporte() {
	switch($_GET["reporte"]) {
		case 1:
			return productosPorCliente();
			break;
		case 2:
			return remitosPorChofer();
			break;
		case 3:
			return productosPorGrupo();
			break;
		default:
			echo '{"status":"error","data":"Operacion no permitida"}';
			break;
	}
}

function productosPorCliente() {
	global $conn;
	
	$fechaArray = explode("/", $_POST['desde']);
	$desde = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	$fechaArray = explode("/", $_POST['hasta']);
	$hasta = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	
	$query = "SELECT * FROM remito, remito_has_producto,producto WHERE remito_idremito=idremito AND producto_idproducto=idproducto ";
	$query .= "AND remito_tipo_idremito_tipo=".$_POST["tipo"];
	$query .= " AND remito_fecha>='$desde' AND remito_fecha<='$hasta'";
	if($_POST['deposito']!=0) {
		$query .= " AND deposito_iddeposito=".$_POST["deposito"];
	}
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$FileName = 'productosPorCliente-' . date("d-m-y") . '.xls';
	$reporteCSV = "id,producto,cantidad,fecha,observaciones\n";
	$result = '{"status":"OK","archivo":"'.$FileName.'","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idremito'].'",';
		$result .= '"producto":"'.$row['producto_nombre'].'",';
		$result .= '"cantidad":"'.$row['ingreso'].'",';
		$fechaArray = explode("-", $row['remito_fecha']);
		$result .= '"fecha":"'.$fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0].'",';
		$result .= '"observaciones":"'.$row['remito_observaciones'].'"';
		$result .= '},';
		$reporteCSV .= $row['idremito'].",".$row['producto_nombre'].",".$row['ingreso'].",".$fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0].",".$row['remito_observaciones']."\n";
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	$NewFile = fopen($FileName,"w+");
	if(fwrite($NewFile, $reporteCSV) === FALSE) { 
		echo "Could not write to CSV file!"; 
		exit();
	} 
	
	return $result;
}

function remitosPorChofer() {
	global $conn;
	
	$fechaArray = explode("/", $_POST['desde']);
	$desde = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	$fechaArray = explode("/", $_POST['hasta']);
	$hasta = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	
	$query = "SELECT * FROM remito, remito_has_producto,producto WHERE remito_idremito=idremito AND producto_idproducto=idproducto ";
	$query .= "AND remito_tipo_idremito_tipo=".$_POST["tipo"]." AND chofer_idchofer=".$_POST["chofer"]." AND vehiculo_idvehiculo=".$_POST["vehiculo"];
	$query .= " AND remito_fecha>='$desde' AND remito_fecha<='$hasta'";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$FileName = 'remitosPorChofer-' . date("d-m-y") . '.xls';
	$reporteCSV = "id,producto,fecha,observaciones\n";
	$result = '{"status":"OK","archivo":"'.$FileName.'","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idremito'].'",';
		$result .= '"producto":"'.$row['producto_nombre'].'",';
		$fechaArray = explode("-", $row['remito_fecha']);
		$result .= '"fecha":"'.$fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0].'",';
		$result .= '"observaciones":"'.$row['remito_observaciones'].'"';
		$result .= '},';
		$reporteCSV .= $row['idremito'].",".$row['producto_nombre'].",".$fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0].",".$row['remito_observaciones']."\n";
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	$NewFile = fopen($FileName,"w+");
	if(fwrite($NewFile, $reporteCSV) === FALSE) { 
		echo "Could not write to CSV file!"; 
		exit();
	} 
	
	return $result;
}

function productosPorGrupo() {
	global $conn;
	
	$fechaArray = explode("/", $_POST['desde']);
	$desde = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	$fechaArray = explode("/", $_POST['hasta']);
	$hasta = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
	
	$query = "SELECT * FROM remito, remito_has_producto,producto WHERE remito_idremito=idremito AND producto_idproducto=idproducto ";
	$query .= "AND remito_tipo_idremito_tipo=".$_POST["tipo"]." AND subgrupo_idsubgrupo=".$_POST["subgrupo"];
	$query .= " AND remito_fecha>='$desde' AND remito_fecha<='$hasta'";
	$recordset = mysql_query($query, $conn) or die(mysql_error());
	
	$FileName = 'productosPorGrupo-' . date("d-m-y") . '.xls';
	$reporteCSV = "id,producto,fecha,observaciones\n";
	$result = '{"status":"OK","archivo":"'.$FileName.'","data":[';
	while($row = mysql_fetch_assoc($recordset)) {
		$result .= '{"id":"'.$row['idremito'].'",';
		$result .= '"producto":"'.$row['producto_nombre'].'",';
		$fechaArray = explode("-", $row['remito_fecha']);
		$result .= '"fecha":"'.$fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0].'",';
		$result .= '"observaciones":"'.$row['remito_observaciones'].'"';
		$result .= '},';
		$reporteCSV .= $row['idremito'].",".$row['producto_nombre'].",".$fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0].",".$row['remito_observaciones']."\n";
	}
	if(mysql_num_rows($recordset)>0) {
		$result = substr($result, 0, strlen($result)-1);
	}
	$result .= ']}';
	
	$NewFile = fopen($FileName,"w+");
	if(fwrite($NewFile, $reporteCSV) === FALSE) { 
		echo "Could not write to CSV file!"; 
		exit();
	} 
	
	return $result;
}

function getReporteCSV() {
	$FileName = $_GET['rep'];

	$NewFile = fopen($FileName,"r");
	$reporte = fread($NewFile, filesize($FileName));
	ob_start(); 
	/*header("Content-type: application/vnd.ms-excel");
	header('Content-Type: application/csv'); 
	header("Content-length: " . filesize($FileName)); 
	header('Content-Disposition: attachment; filename="' . $FileName . '"'); */
	echo $reporte;
	fclose($NewFile);
	ob_end_flush();	
	exit();
}
?>