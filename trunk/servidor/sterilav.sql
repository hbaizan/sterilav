SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `aplicacion` (
  `idaplicacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) DEFAULT NULL,
  `link_aplicacion` varchar(50) DEFAULT NULL,
  `grupo_idgrupopantalla` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idaplicacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES
(1, 'Personas', 'usuario', 1),
(2, 'Lista de Personas', 'listaUsuarios', 1),
(3, 'Nuevo Remito', 'remitoEntrada', 4),
(4, 'Lista de Depositos', 'listaDepositos', 2),
(5, 'Producto', 'producto', 3),
(6, 'Lista de Productos', 'listaProductos', 3),
(7, 'Chofer', 'chofer', 1),
(8, 'Deposito', 'deposito', 2),
(9, 'Empresa', 'empresa', 2),
(10, 'Lista de Empresas', 'listaEmpresas', 2),
(11, 'Grupo', 'grupo', 3),
(12, 'Lista de Grupos', 'listaGrupos', 3),
(13, 'Lista de Remitos', 'listaRemitos', 4),
(14, 'Vehiculo', 'vehiculo', 2),
(15, 'Lista de Vehiculos', 'listaVehiculos', 2),
(16, 'Lista de Choferes', 'listaChoferes', 1),
(18, 'Subgrupo', 'subgrupo', 3),
(19, 'Lista de Subgrupos', 'listaSubgrupos', 3),
(20, 'Productos por Cliente', 'productosPorCliente', 5),
(21, 'Remitos por Chofer', 'remitosPorChofer', 5),
(22, 'Productos por Grupo', 'productosPorGrupo', 5),
(23, 'Clientes por Capita', 'clientesPorCapita', 5);

CREATE TABLE IF NOT EXISTS `chofer` (
  `idchofer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chofer_nombre` varchar(20) DEFAULT NULL,
  `chofer_apellido` varchar(20) DEFAULT NULL,
  `chofer_inactivo` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idchofer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `departamento` (
  `iddepartamento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provincia_idprovincia` int(10) unsigned NOT NULL,
  `departamento_nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`iddepartamento`),
  KEY `departamento_FKIndex1` (`provincia_idprovincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `departamento` (`iddepartamento`, `provincia_idprovincia`, `departamento_nombre`) VALUES
(1, 1, 'Guaymallen'),
(2, 1, 'Godoy Cruz');

CREATE TABLE IF NOT EXISTS `deposito` (
  `iddeposito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `departamento_iddepartamento` int(10) unsigned NOT NULL,
  `empresa_idempresa` int(10) unsigned NOT NULL,
  `deposito_nombre` varchar(50) DEFAULT NULL,
  `deposito_domicilio` varchar(50) DEFAULT NULL,
  `deposito_inactivo` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`iddeposito`),
  KEY `deposito_FKIndex1` (`empresa_idempresa`),
  KEY `deposito_FKIndex2` (`departamento_iddepartamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `empresa` (
  `idempresa` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iva_idiva` int(10) unsigned NOT NULL,
  `empresa_razon_social` varchar(50) DEFAULT NULL,
  `empresa_cuit` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`idempresa`),
  KEY `empresa_FKIndex1` (`iva_idiva`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `grupo` (
  `idgrupo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grupo_nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idgrupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `grupopantalla` (
  `idgrupopantalla` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idgrupopantalla`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `grupopantalla` (`idgrupopantalla`, `nombre`) VALUES
(1, 'Personas'),
(2, 'Clientes'),
(3, 'Productos'),
(4, 'Administracion'),
(5, 'Reportes');

CREATE TABLE IF NOT EXISTS `iva` (
  `idiva` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iva_condicion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idiva`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `iva` (`idiva`, `iva_condicion`) VALUES
(1, 'Inscripto'),
(2, 'No Inscripto');

CREATE TABLE IF NOT EXISTS `perfil` (
  `idperfil` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `perfil_nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idperfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `perfil` (`idperfil`, `perfil_nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario'),
(3, 'Contador'),
(4, 'Cliente');

CREATE TABLE IF NOT EXISTS `permiso` (
  `aplicacion_idaplicacion` int(10) unsigned NOT NULL,
  `perfil_idperfil` int(10) unsigned NOT NULL,
  PRIMARY KEY (`aplicacion_idaplicacion`,`perfil_idperfil`),
  KEY `aplicacion_has_perfil_FKIndex1` (`aplicacion_idaplicacion`),
  KEY `aplicacion_has_perfil_FKIndex2` (`perfil_idperfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `permiso` (`aplicacion_idaplicacion`, `perfil_idperfil`) VALUES
(1, 1),
(2, 1),
(2, 2),
(3, 1),
(4, 1),
(4, 2),
(5, 1),
(6, 1),
(6, 2),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(10, 2),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1);

CREATE TABLE IF NOT EXISTS `persona` (
  `idpersona` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `departamento_iddepartamento` int(10) unsigned NOT NULL,
  `perfil_idperfil` int(10) unsigned NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `clave` varchar(20) DEFAULT NULL,
  `persona_inactivo` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idpersona`),
  KEY `persona_FKIndex2` (`perfil_idperfil`),
  KEY `persona_FKIndex3` (`departamento_iddepartamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `persona` (`idpersona`, `departamento_iddepartamento`, `perfil_idperfil`, `nombre`, `apellido`, `usuario`, `clave`, `persona_inactivo`) VALUES
(1, 1, 1, 'sterilav', 'admin', 'sterilav', 'sterilav', 0);

CREATE TABLE IF NOT EXISTS `producto` (
  `idproducto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subgrupo_idsubgrupo` int(10) unsigned NOT NULL,
  `producto_nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idproducto`),
  KEY `producto_FKIndex1` (`subgrupo_idsubgrupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

CREATE TABLE IF NOT EXISTS `producto_has_deposito` (
  `producto_idproducto` int(10) unsigned NOT NULL,
  `deposito_iddeposito` int(10) unsigned NOT NULL,
  `cantidad_tope` int(10) unsigned DEFAULT NULL,
  `deposito_capita` int(10) unsigned DEFAULT NULL,
  `stock_inicial` int(10) unsigned NOT NULL,
  PRIMARY KEY (`producto_idproducto`,`deposito_iddeposito`),
  KEY `producto_has_deposito_FKIndex1` (`producto_idproducto`),
  KEY `producto_has_deposito_FKIndex2` (`deposito_iddeposito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `provincia` (
  `idprovincia` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idprovincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `provincia` (`idprovincia`, `nombre`) VALUES
(1, 'Mendoza'),
(2, 'San Juan');

CREATE TABLE IF NOT EXISTS `remito` (
  `idremito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `persona_idpersona` int(10) unsigned NOT NULL,
  `deposito_iddeposito` int(10) unsigned NOT NULL,
  `chofer_idchofer` int(10) unsigned NOT NULL,
  `remito_tipo_idremito_tipo` int(10) unsigned NOT NULL,
  `vehiculo_idvehiculo` int(10) unsigned NOT NULL,
  `remito_fecha` date DEFAULT NULL,
  `remito_hora_entrega` time DEFAULT NULL,
  `remito_hora_retiro` time DEFAULT NULL,
  `remito_observaciones` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idremito`),
  KEY `remito_FKIndex1` (`vehiculo_idvehiculo`),
  KEY `remito_FKIndex2` (`remito_tipo_idremito_tipo`),
  KEY `remito_FKIndex3` (`chofer_idchofer`),
  KEY `remito_FKIndex4` (`deposito_iddeposito`),
  KEY `remito_FKIndex5` (`persona_idpersona`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

CREATE TABLE IF NOT EXISTS `remito_has_producto` (
  `remito_idremito` int(10) unsigned NOT NULL,
  `producto_idproducto` int(10) unsigned NOT NULL,
  `ingreso` int(10) unsigned DEFAULT NULL,
  `cantidad_faltante` int(10) unsigned DEFAULT NULL,
  `egreso` int(10) unsigned NOT NULL,
  PRIMARY KEY (`remito_idremito`,`producto_idproducto`),
  KEY `remito_has_producto_FKIndex1` (`remito_idremito`),
  KEY `remito_has_producto_FKIndex2` (`producto_idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `remito_tipo` (
  `idremito_tipo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `remito_tipo_nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idremito_tipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `remito_tipo` (`idremito_tipo`, `remito_tipo_nombre`) VALUES
(1, 'Remito de Entrada'),
(2, 'Orden de Trabajo'),
(3, 'Remito de Salida'),
(4, 'Ajuste'),
(5, 'Recomposicion');

CREATE TABLE IF NOT EXISTS `subgrupo` (
  `idsubgrupo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grupo_idgrupo` int(10) unsigned NOT NULL,
  `subgrupo_nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idsubgrupo`),
  KEY `Subgrupo_FKIndex1` (`grupo_idgrupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `vehiculo` (
  `idvehiculo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehiculo_patente` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idvehiculo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


ALTER TABLE `subgrupo`
  ADD CONSTRAINT `subgrupo_ibfk_1` FOREIGN KEY (`grupo_idgrupo`) REFERENCES `grupo` (`idgrupo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
