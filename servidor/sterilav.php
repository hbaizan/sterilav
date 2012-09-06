<?php require_once('./db.php');
require_once('./grupo.php');
require_once('./perfil.php');
require_once('./producto.php');
require_once('./usuario.php');
require_once('./iva.php');
require_once('./departamento.php');
require_once('./provincia.php');
require_once('./chofer.php');
require_once('./deposito.php');
require_once('./empresa.php');
require_once('./vehiculo.php');
require_once('./remito.php');

mysql_select_db($database_conn, $conn);

if(!isset($_GET['op'])) {
	echo "Operacion no permitida";
	exit;
}
switch($_GET['op']) {
	case "listaRemitos":
		echo listaRemitos();
		break;
	case "putRemito":
		echo putRemito();
		break;
	case "updateRemito":
		echo updateRemito();
		break;
	case "listaGrupos":
		echo listaGrupos();
		break;
	case "getGrupo":
		echo getGrupo($_GET['id']);
		break;
	case "putGrupo":
		echo putGrupo();
		break;
	case "updateGrupo":
		echo updateGrupo();
		break;
	case "listaPerfiles":
		echo listaPerfiles();
		break;
	case "listaPermisos":
		echo listaPermisos($_GET['id']);
		break;
	case "getPerfil":
		echo getPerfil($_GET['id']);
		break;
	case "listaProductos":
		echo listaProductos();
		break;
	case "getProducto":
		echo getProducto($_GET['id']);
		break;
	case "putProducto":
		echo putProducto();
		break;
	case "updateProducto":
		echo updateProducto();
		break;
	case "listaUsuarios":
		echo listaUsuarios();
		break;
	case "getUsuario":
		echo getUsuario($_GET['id']);
		break;
	case "putUsuario":
		echo putUsuario();
		break;
	case "updateUsuario":
		echo updateUsuario();
		break;
	case "validarUsuario":
		echo validarUsuario($_GET['usuario'],$_GET['password']);
		break;	
	case "listaIvas":
		echo listaIvas();
		break;
	case "getIva":
		echo getIva($_GET['id']);
		break;
	case "listaDepartamentos":
		echo listaDepartamentos();
		break;
	case "getDepartamento":
		echo getDepartamento($_GET['id']);
		break;
	case "listaProvincias":
		echo listaProvincias();
		break;
	case "getProvincia":
		echo getProvincia($_GET['id']);
		break;
	case "putProvincia":
		echo putProvincia();
		break;
	case "updateProvincia":
		echo updateProvincia();
		break;
	case "listaChoferes":
		echo listaChoferes();
		break;
	case "getChofer":
		echo getChofer($_GET['id']);
		break;
	case "putChofer":
		echo putChofer();
		break;
	case "updateChofer":
		echo updateChofer();
		break;
	case "listaEmpresas":
		echo listaEmpresas();
		break;
	case "getEmpresa":
		echo getEmpresa($_GET['id']);
		break;
	case "putEmpresa":
		echo putEmpresa();
		break;
	case "updateEmpresa":
		echo updateEmpresa();
		break;
	case "listaVehiculos":
		echo listaVehiculos();
		break;
	case "getVehiculo":
		echo getVehiculo($_GET['id']);
		break;
	case "putVehiculo":
		echo putVehiculo();
		break;
	case "updateVehiculo":
		echo updateVehiculo();
		break;
	case "listaDepositos":
		echo listaDepositos();
		break;
	case "getDeposito":
		echo getDeposito($_GET['id']);
		break;
	case "putDeposito":
		echo putDeposito();
		break;
	case "updateDeposito":
		echo updateDeposito();
		break;
}

function chequearCampo($campo) {
	if(!isset($campo) || $campo=="") {
		echo '{"status":"error","data":"El campo '.$campo.' no puede estar vacio"}';
		exit();
	}
	return $campo;
}

?>