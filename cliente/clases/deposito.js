function CDeposito() {
	var self = this;
	
	self.id = ko.observable();
	self.nombre = ko.observable().extend({required: true});
	self.domicilio = ko.observable().extend({required: true});
	self.departamento = ko.observable().extend({required: true});
	self.empresa = ko.observable().extend({required: true});
	self.remito = ko.observable(false).extend({required: true});
	self.activo = ko.observable(false).extend({required: true});		
	self.productos = ko.observableArray();
	
	self.cargar = function(jsonData) {
		
		if(jsonData) {
			self.id(jsonData.id);
			self.nombre(jsonData.nombre);
			self.domicilio(jsonData.domicilio);
			self.departamento(jsonData.departamento);
			self.empresa(jsonData.idempresa);
			self.remito(jsonData.remito);
			self.activo(jsonData.activo);
		}
	};
	
	self.toJson = function() {
		var deposito = {};
		deposito.id = self.id();
		deposito.nombre = self.idnombre;
		deposito.domicilio = self.domicilio();
		deposito.departamento = self.departamento();
		deposito.empresa = self.empresa().toJson();
		deposito.remito = self.remito();
		deposito.activo = self.activo();
		
		return deposito;
	};
	
	self.mockup = function(idEmpresa) {
		self.id(0);
		self.nombre("deposito");
		self.domicilio("domicilio");
		self.departamento(1);
		self.empresa(idEmpresa);
		self.remito("");
		self.activo(1);
	};
	
	self.toString = function() {
	};
	self.salvar = function() {	
	};
}
	