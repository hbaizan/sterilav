<?

include "./db.php";


function listaUsuarios() {
	$query = mysql_query($coneccion, "select * from usuarios");
	$result = '"result": {[';
	while($row = mysql_fetch_array($query)) {
		$result .= '{"id":"'.$row['id'].'",';
		$result .= '"nombre":"'.$row['nombre'].'",';
		$result .= '"apellido":"'.$row['apellido'].'",';
		$result .= '"password":"'.$row['password'].'",';
		$result .= '"usuario":"'.$row['usuario'].'"';
		$result .= '},';
	}
	$result .= ']}';

	echo $result;
}
?>
