-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 16, 2012 at 01:24 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `sterilav`
--

CREATE TABLE iva (
  idiva INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  iva_condicion VARCHAR() NULL,
  PRIMARY KEY(idiva)
);

CREATE TABLE grupo (
  idgrupo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  grupo_nombre VARCHAR NULL,
  PRIMARY KEY(idgrupo)
);

CREATE TABLE perfil (
  idperfil INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  perfil_nombre VARCHAR NULL,
  PRIMARY KEY(idperfil)
);

CREATE TABLE provincia (
  idprovincia INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR NULL,
  PRIMARY KEY(idprovincia)
);

CREATE TABLE remito_tipo (
  idremito_tipo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  remito_tipo_nombre VARCHAR NULL,
  PRIMARY KEY(idremito_tipo)
);

CREATE TABLE chofer (
  idchofer INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  chofer_nombre VARCHAR NULL,
  chofer_apellido VARCHAR NULL,
  PRIMARY KEY(idchofer)
);

CREATE TABLE vehiculo (
  idvehiculo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  vehiculo_patente VARCHAR NULL,
  PRIMARY KEY(idvehiculo)
);

CREATE TABLE aplicaciones (
  idaplicaciones INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR NULL,
  link_aplicacion VARCHAR NULL,
  PRIMARY KEY(idaplicaciones)
);

CREATE TABLE empresa (
  idempresa INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  iva_idiva INTEGER UNSIGNED NOT NULL,
  empresa_razon_social VARCHAR NULL,
  empresa_cuit VARCHAR NULL,
  PRIMARY KEY(idempresa),
  INDEX empresa_FKIndex1(iva_idiva),
  FOREIGN KEY(iva_idiva)
    REFERENCES iva(idiva)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE departamento (
  iddepartamento INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  provincia_idprovincia INTEGER UNSIGNED NOT NULL,
  departamento_nombre VARCHAR NULL,
  PRIMARY KEY(iddepartamento),
  INDEX departamento_FKIndex1(provincia_idprovincia),
  FOREIGN KEY(provincia_idprovincia)
    REFERENCES provincia(idprovincia)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE Subgrupo (
  idsubgrupo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  grupo_idgrupo INTEGER UNSIGNED NOT NULL,
  subgrupo_nombre VARCHAR(45) NULL,
  PRIMARY KEY(idsubgrupo),
  INDEX Subgrupo_FKIndex1(grupo_idgrupo),
  FOREIGN KEY(grupo_idgrupo)
    REFERENCES grupo(idgrupo)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE permisos (
  aplicaciones_idaplicaciones INTEGER UNSIGNED NOT NULL,
  perfil_idperfil INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(aplicaciones_idaplicaciones, perfil_idperfil),
  INDEX aplicaciones_has_perfil_FKIndex1(aplicaciones_idaplicaciones),
  INDEX aplicaciones_has_perfil_FKIndex2(perfil_idperfil),
  FOREIGN KEY(aplicaciones_idaplicaciones)
    REFERENCES aplicaciones(idaplicaciones)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(perfil_idperfil)
    REFERENCES perfil(idperfil)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE deposito (
  iddeposito INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  departamento_iddepartamento INTEGER UNSIGNED NOT NULL,
  empresa_idempresa INTEGER UNSIGNED NOT NULL,
  deposito_nombre VARCHAR NULL,
  deposito_domicilio VARCHAR NULL,
  deposito_tiene_faltante BOOL NULL,
  deposito_inactivo BOOL NULL,
  PRIMARY KEY(iddeposito),
  INDEX deposito_FKIndex1(empresa_idempresa),
  INDEX deposito_FKIndex2(departamento_iddepartamento),
  FOREIGN KEY(empresa_idempresa)
    REFERENCES empresa(idempresa)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(departamento_iddepartamento)
    REFERENCES departamento(iddepartamento)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE persona (
  idpersona INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  departamento_iddepartamento INTEGER UNSIGNED NOT NULL,
  perfil_idperfil INTEGER UNSIGNED NOT NULL,
  nombre VARCHAR NOT NULL,
  apellido VARCHAR NULL,
  usuario VARCHAR NULL,
  clave VARCHAR NULL,
  persona_inactivo BOOL NULL,
  PRIMARY KEY(idpersona),
  INDEX persona_FKIndex2(perfil_idperfil),
  INDEX persona_FKIndex3(departamento_iddepartamento),
  FOREIGN KEY(perfil_idperfil)
    REFERENCES perfil(idperfil)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(departamento_iddepartamento)
    REFERENCES departamento(iddepartamento)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE remito (
  idremito INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  persona_idpersona INTEGER UNSIGNED NOT NULL,
  deposito_iddeposito INTEGER UNSIGNED NOT NULL,
  chofer_idchofer INTEGER UNSIGNED NOT NULL,
  remito_tipo_idremito_tipo INTEGER UNSIGNED NOT NULL,
  vehiculo_idvehiculo INTEGER UNSIGNED NOT NULL,
  remito_fecha DATE NULL,
  remito_hora_entrega DATE NULL,
  remito_hora_retiro DATE NULL,
  remito_observaciones VARCHAR NULL,
  PRIMARY KEY(idremito),
  INDEX remito_FKIndex1(vehiculo_idvehiculo),
  INDEX remito_FKIndex2(remito_tipo_idremito_tipo),
  INDEX remito_FKIndex3(chofer_idchofer),
  INDEX remito_FKIndex4(deposito_iddeposito),
  INDEX remito_FKIndex5(persona_idpersona),
  FOREIGN KEY(vehiculo_idvehiculo)
    REFERENCES vehiculo(idvehiculo)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(remito_tipo_idremito_tipo)
    REFERENCES remito_tipo(idremito_tipo)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(chofer_idchofer)
    REFERENCES chofer(idchofer)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(deposito_iddeposito)
    REFERENCES deposito(iddeposito)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(persona_idpersona)
    REFERENCES persona(idpersona)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE producto (
  idproducto INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Subgrupo_idsubgrupo INTEGER UNSIGNED NOT NULL,
  producto_nombre VARCHAR NULL,
  PRIMARY KEY(idproducto),
  INDEX producto_FKIndex1(Subgrupo_idsubgrupo),
  FOREIGN KEY(Subgrupo_idsubgrupo)
    REFERENCES Subgrupo(idsubgrupo)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE remito_has_producto (
  remito_idremito INTEGER UNSIGNED NOT NULL,
  producto_idproducto INTEGER UNSIGNED NOT NULL,
  cantidad_real INTEGER UNSIGNED NULL,
  cantidad_faltante INTEGER UNSIGNED NULL,
  PRIMARY KEY(remito_idremito, producto_idproducto),
  INDEX remito_has_producto_FKIndex1(remito_idremito),
  INDEX remito_has_producto_FKIndex2(producto_idproducto),
  FOREIGN KEY(remito_idremito)
    REFERENCES remito(idremito)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(producto_idproducto)
    REFERENCES producto(idproducto)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE producto_has_deposito (
  producto_idproducto INTEGER UNSIGNED NOT NULL,
  deposito_iddeposito INTEGER UNSIGNED NOT NULL,
  cantidad_tope INTEGER UNSIGNED NULL,
  deposito_capita INTEGER UNSIGNED NULL,
  stock_inicial INTEGER UNSIGNED NULL,
  PRIMARY KEY(producto_idproducto, deposito_iddeposito),
  INDEX producto_has_deposito_FKIndex1(producto_idproducto),
  INDEX producto_has_deposito_FKIndex2(deposito_iddeposito),
  FOREIGN KEY(producto_idproducto)
    REFERENCES producto(idproducto)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(deposito_iddeposito)
    REFERENCES deposito(iddeposito)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);


-- --------------------------------------------------------

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
(17, 'Subgrupo', 'subgrupo', 3),
(18, 'Lista de Subgrupos', 'listaSubgrupos', 3);

-- --------------------------------------------------------

INSERT INTO `departamento` (`iddepartamento`, `provincia_idprovincia`, `departamento_nombre`) VALUES
(1, 1, 'Guaymallen'),
(2, 1, 'Godoy Cruz'),
(3, 1, 'Mendoza'),
(4, 1, 'Luján de Cuyo'),
(5, 1, 'Maipú'),
(6, 1, 'San Martín'),
(7, 1, 'Junín'),
(8, 1, 'San Rafael'),
(9, 1, 'Gral. Alvear'),
(10, 1, 'Malargüe');

-- --------------------------------------------------------

INSERT INTO `grupo` (`idgrupo`, `grupo_nombre`) VALUES
(1, 'QUIROFANO');

-- --------------------------------------------------------

INSERT INTO `subgrupo` (`idsubgrupo`, `subgrupo_nombre`, `grupo_idgrupo`) VALUES
(1, 'Packs', 1);

-- --------------------------------------------------------

INSERT INTO `grupopantalla` (`idgrupopantalla`, `nombre`) VALUES
(1, 'Personas'),
(2, 'Clientes'),
(3, 'Productos'),
(4, 'Administracion');

-- --------------------------------------------------------

INSERT INTO `iva` (`idiva`, `iva_condicion`) VALUES
(1, 'Inscripto'),
(2, 'No Inscripto');

-- --------------------------------------------------------

INSERT INTO `perfil` (`idperfil`, `perfil_nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario'),
(3, 'Contador'),
(4, 'Cliente');

-- --------------------------------------------------------

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
(18, 1);

-- --------------------------------------------------------

INSERT INTO `persona` (`idpersona`, `departamento_iddepartamento`, `perfil_idperfil`, `nombre`, `apellido`, `usuario`, `clave`, `persona_inactivo`) VALUES
(1, 1, 1, 'sterilav', 'admin', 'sterilav', 'sterilav', 0);

-- --------------------------------------------------------

INSERT INTO `producto` (`idproducto`, `subgrupo_idsubgrupo`, `producto_nombre`) VALUES
(1, 1, 'Pack cirugia grande '),
(2, 1, 'Pack cirugia mediano'),
(3, 1, 'Pack cirugia chico e'),
(4, 1, 'Pack parto'),
(5, 1, 'Pack cesarea'),
(6, 1, 'Pack cirugia descart'),
(7, 1, 'Pack anestesia ester'),
(8, 1, 'Blusones cirugia est'),
(9, 1, 'Campo grande esteril'),
(10, 1, 'Campo chico esteril'),
(11, 1, 'Campo grande fenstra'),
(12, 1, 'Campo chico fenestra'),
(13, 1, 'Pantalones'),
(14, 1, 'Chaquetas'),
(15, 1, 'Ambos de cirugia'),
(16, 1, 'Bata paciente'),
(17, 1, 'Pierneras'),
(18, 1, 'Guardapolvos'),
(19, 1, 'Camilleros'),
(20, 1, 'Atapaquetes');

-- --------------------------------------------------------

INSERT INTO `provincia` (`idprovincia`, `nombre`) VALUES
(1, 'Mendoza'),
(2, 'San Juan');

-- --------------------------------------------------------

INSERT INTO `remito_tipo` (`idremito_tipo`, `remito_tipo_nombre`) VALUES
(1, 'Remito de Entrada'),
(2, 'Orden de Trabajo'),
(3, 'Remito de Salida'),
(4, 'Ajuste'),
(5, 'Recomposicion');

-- --------------------------------------------------------