-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 28, 2012 at 04:48 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sterilav`
--

-- --------------------------------------------------------

--
-- Table structure for table `articulo`
--

CREATE TABLE IF NOT EXISTS `articulo` (
  `idArticulo` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `estado` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `codigoBarra` varchar(20) NOT NULL,
  PRIMARY KEY (`idArticulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `idCliente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `calle` varchar(50) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `codigoPostal` int(11) NOT NULL,
  `condicionIva` int(11) NOT NULL,
  `cuit` varchar(13) NOT NULL,
  `tipoCliente` int(11) NOT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `detalleremito`
--

CREATE TABLE IF NOT EXISTS `detalleremito` (
  `idDetalleRemito` int(11) NOT NULL,
  `idRemito` int(11) NOT NULL,
  `idArticulo` int(11) NOT NULL,
  `entrega` int(11) NOT NULL,
  `recibe` int(11) NOT NULL,
  PRIMARY KEY (`idDetalleRemito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `detalleventa`
--

CREATE TABLE IF NOT EXISTS `detalleventa` (
  `idDetalle` int(11) NOT NULL,
  `idVenta` int(11) NOT NULL,
  `idArticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`idDetalle`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grupopantallas`
--

CREATE TABLE IF NOT EXISTS `grupopantallas` (
  `idPantalla` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`idPantalla`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `grupopantallas`
--

INSERT INTO `grupopantallas` (`idPantalla`, `nombre`) VALUES
(1, 'Usuarios'),
(2, 'Clientes'),
(3, 'Productos'),
(4, 'Administracion');

-- --------------------------------------------------------

--
-- Table structure for table `pantallas`
--

CREATE TABLE IF NOT EXISTS `pantallas` (
  `idPantalla` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `aplicacion` varchar(50) NOT NULL,
  PRIMARY KEY (`idPantalla`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `pantallas`
--

INSERT INTO `pantallas` (`idPantalla`, `nombre`, `aplicacion`) VALUES
(1, 'Usuarios', 'usuario'),
(2, 'Lista de Usuarios', 'listaUsuarios'),
(3, 'Remito', 'remito'),
(4, 'Cliente', 'cliente'),
(5, 'Lista de Clientes', 'listaclientes'),
(6, 'Productos', 'producto'),
(7, 'Lista de Productos', 'listaproductos');

-- --------------------------------------------------------

--
-- Table structure for table `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `idPerfil` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) NOT NULL,
  PRIMARY KEY (`idPerfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `perfil`
--

INSERT INTO `perfil` (`idPerfil`, `descripcion`) VALUES
(1, 'Administrador'),
(2, 'Usuario'),
(3, 'Contador'),
(4, 'Cliente');

-- --------------------------------------------------------

--
-- Table structure for table `perfilpermiso`
--

CREATE TABLE IF NOT EXISTS `perfilpermiso` (
  `idPermiso` int(11) NOT NULL AUTO_INCREMENT,
  `idPerfil` int(11) NOT NULL,
  `idPantalla` int(11) NOT NULL,
  `idGrupo` int(11) NOT NULL,
  PRIMARY KEY (`idPermiso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `perfilpermiso`
--

INSERT INTO `perfilpermiso` (`idPermiso`, `idPerfil`, `idPantalla`, `idGrupo`) VALUES
(1, 2, 2, 1),
(2, 1, 1, 1),
(3, 2, 5, 2),
(4, 2, 7, 3),
(5, 1, 5, 2),
(6, 1, 7, 3),
(7, 1, 2, 1),
(8, 1, 4, 2),
(9, 1, 6, 3),
(10, 1, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `remito`
--

CREATE TABLE IF NOT EXISTS `remito` (
  `idRemito` int(11) NOT NULL,
  `numeroRemito` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  PRIMARY KEY (`idRemito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `legajo` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `perfil` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `legajo`, `nombre`, `apellido`, `perfil`, `usuario`, `password`) VALUES
(2, '0000000001', 'sterilav', 'admin editado', 1, 'sterilav', 'sterilav'),
(5, '0000000002', 'otro', 'otro', 2, 'otro', 'otro');

-- --------------------------------------------------------

--
-- Table structure for table `venta`
--

CREATE TABLE IF NOT EXISTS `venta` (
  `idVenta` int(11) NOT NULL,
  `condicion` varchar(10) NOT NULL,
  `numeroFactura` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`idVenta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
