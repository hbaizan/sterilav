CREATE TABLE iva (
idiva INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
iva_condicion VARCHAR(20) NULL,
PRIMARY KEY(idiva)
);

CREATE TABLE grupo (
  idgrupo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  grupo_nombre VARCHAR(20) NULL,
  PRIMARY KEY(idgrupo)
);

CREATE TABLE perfil (
  idperfil INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  perfil_nombre VARCHAR(20) NULL,
  PRIMARY KEY(idperfil)
);

CREATE TABLE provincia (
  idprovincia INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NULL,
  PRIMARY KEY(idprovincia)
);

CREATE TABLE remito_tipo (
  idremito_tipo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  remito_tipo_nombre VARCHAR(20) NULL,
  PRIMARY KEY(idremito_tipo)
);

CREATE TABLE chofer (
  idchofer INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  chofer_nombre VARCHAR(50) NULL,
  chofer_apellido VARCHAR(50) NULL,
  PRIMARY KEY(idchofer)
);

CREATE TABLE grupopantalla (
  idgrupopantalla INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NULL,
  PRIMARY KEY(idgrupopantalla)
);

CREATE TABLE aplicacion (
  idaplicacion INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR(50) NULL,
  link_aplicacion VARCHAR(50) NULL,
  grupo_idgrupopantalla INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(idaplicacion)
);

CREATE TABLE empresa (
  idempresa INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  empresa_razon_social VARCHAR(50) NULL,
  empresa_cuit VARCHAR(11) NULL,
  PRIMARY KEY(idempresa)
);

CREATE TABLE vehiculo (
  idvehiculo INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  vehiculo_patente VARCHAR(10) NULL,
  PRIMARY KEY(idvehiculo)
);

CREATE TABLE producto (
  idproducto INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  grupo_idgrupo INTEGER UNSIGNED NOT NULL,
  producto_nombre VARCHAR(50) NULL,
  PRIMARY KEY(idproducto),
  INDEX producto_FKIndex1(grupo_idgrupo),
  FOREIGN KEY(grupo_idgrupo)
    REFERENCES grupo(idgrupo)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE departamento (
  iddepartamento INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  provincia_idprovincia INTEGER UNSIGNED NOT NULL,
  departamento_nombre VARCHAR(50) NULL,
  PRIMARY KEY(iddepartamento),
  INDEX departamento_FKIndex1(provincia_idprovincia),
  FOREIGN KEY(provincia_idprovincia)
    REFERENCES provincia(idprovincia)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE permiso (
  aplicacion_idaplicacion INTEGER UNSIGNED NOT NULL,
  perfil_idperfil INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(aplicacion_idaplicacion, perfil_idperfil),
  INDEX aplicacion_has_perfil_FKIndex1(aplicacion_idaplicacion),
  INDEX aplicacion_has_perfil_FKIndex2(perfil_idperfil),
  FOREIGN KEY(aplicacion_idaplicacion)
    REFERENCES aplicacion(idaplicacion)
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
  deposito_nombre VARCHAR(50) NULL,
  deposito_domicilio VARCHAR(50) NULL,
  deposito_tiene_faltante BOOL NULL,
  deposito_capita INTEGER UNSIGNED NULL,
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

CREATE TABLE producto_has_deposito (
  producto_idproducto INTEGER UNSIGNED NOT NULL,
  deposito_iddeposito INTEGER UNSIGNED NOT NULL,
  cantidad_tope INTEGER UNSIGNED NULL,
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

CREATE TABLE persona (
  idpersona INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  departamento_iddepartamento INTEGER UNSIGNED NOT NULL,
  perfil_idperfil INTEGER UNSIGNED NOT NULL,
  iva_idiva INTEGER UNSIGNED NOT NULL,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NULL,
  usuario VARCHAR(50) NULL,
  clave VARCHAR(50) NULL,
  PRIMARY KEY(idpersona),
  INDEX persona_FKIndex1(iva_idiva),
  INDEX persona_FKIndex2(perfil_idperfil),
  INDEX persona_FKIndex3(departamento_iddepartamento),
  FOREIGN KEY(iva_idiva)
    REFERENCES iva(idiva)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
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
  deposito_iddeposito INTEGER UNSIGNED NOT NULL,
  chofer_idchofer INTEGER UNSIGNED NOT NULL,
  remito_tipo_idremito_tipo INTEGER UNSIGNED NOT NULL,
  vehiculo_idvehiculo INTEGER UNSIGNED NOT NULL,
  remito_fecha DATE NULL,
  remito_hora_entrega DATE NULL,
  remito_hora_retiro DATE NULL,
  remito_observaciones VARCHAR(255) NULL,
  PRIMARY KEY(idremito),
  INDEX remito_FKIndex1(vehiculo_idvehiculo),
  INDEX remito_FKIndex2(remito_tipo_idremito_tipo),
  INDEX remito_FKIndex3(chofer_idchofer),
  INDEX remito_FKIndex4(deposito_iddeposito),
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


INSERT INTO `grupopantalla` (`idgrupopantalla`, `nombre`) VALUES(1, 'Personas');
INSERT INTO `grupopantalla` (`idgrupopantalla`, `nombre`) VALUES(2, 'Clientes');
INSERT INTO `grupopantalla` (`idgrupopantalla`, `nombre`) VALUES(3, 'Productos');
INSERT INTO `grupopantalla` (`idgrupopantalla`, `nombre`) VALUES(4, 'Administracion');

INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(1, 'Personas', 'usuario',1);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(2, 'Lista de Personas', 'listaUsuarios',1);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(3, 'Remito', 'remito',4);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(4, 'Lista de Depositos', 'listaDepositos',2);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(5, 'Producto', 'producto',3);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(6, 'Lista de Productos', 'listaProductos',3);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(7, 'Chofer', 'chofer',1);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(8, 'Deposito', 'deposito',2);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(9, 'Empresa', 'empresa',2);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(10, 'Lista de Empresas', 'listaEmpresas',2);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(11, 'Grupo', 'grupo',3);
INSERT INTO `aplicacion` (`idaplicacion`, `descripcion`, `link_aplicacion`, `grupo_idgrupopantalla`) VALUES(12, 'Lista de Grupos', 'listaGrupos',3);

INSERT INTO `perfil` (`idperfil`, `perfil_nombre`) VALUES(1, 'Administrador');
INSERT INTO `perfil` (`idperfil`, `perfil_nombre`) VALUES(2, 'Usuario');
INSERT INTO `perfil` (`idperfil`, `perfil_nombre`) VALUES(3, 'Contador');
INSERT INTO `perfil` (`idperfil`, `perfil_nombre`) VALUES(4, 'Cliente');

INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(2, 2);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(2, 4);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(2, 6);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(2, 10);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 1);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 2);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 3);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 4);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 5);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 6);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 7);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 8);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 9);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 10);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 11);
INSERT INTO `permiso` (`perfil_idperfil`, `aplicacion_idaplicacion`) VALUES(1, 12);

INSERT INTO `provincia` (`idprovincia`, `nombre`) VALUES(1, 'Mendoza');
INSERT INTO `provincia` (`idprovincia`, `nombre`) VALUES(2, 'San Juan');

INSERT INTO `departamento` (`iddepartamento`, `provincia_idprovincia`, `departamento_nombre`) VALUES(1, 1, 'Guaymallén');
INSERT INTO `departamento` (`iddepartamento`, `provincia_idprovincia`, `departamento_nombre`) VALUES(2, 1, 'Godoy Cruz');

INSERT INTO `iva` (`idiva`, `iva_condicion`) VALUES(1, 'Inscripto');
INSERT INTO `iva` (`idiva`, `iva_condicion`) VALUES(2, 'No Inscripto');

INSERT INTO `persona` (`idpersona`, `nombre`, `apellido`, `perfil_idperfil`, `usuario`, `clave`, `departamento_iddepartamento`, `iva_idiva`) VALUES(1, 'sterilav', 'admin', 1, 'sterilav', 'sterilav', 1, 1);