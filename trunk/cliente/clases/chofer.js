function CChofer() {
	var self = this;
	
	self.id = ko.observable();
	self.nombre = ko.observable().extend({required: true});
	self.apellido = ko.observable().extend({required: true});
	self.nombreCompleto = ko.computed(function() {
		return self.nombre() + " " + self.apellido();
	});

	self.cargar = function(jsonData) {
		console.log("inicia carga Chofer");
		if(jsonData) {
			self.id(jsonData.id);
			self.nombre(jsonData.nombre);
			self.apellido(jsonData.apellido);
		}
	};
	
	self.toJson = function() {
		var chofer = {};
		chofer.id = self.id();
		chofer.nombre = self.nombre();
		chofer.apellido = self.apellido();
		
		return chofer;
	};

	self.toString = function() {
		var chofer = '{"id":"'+self.id()+'"nombre":"'+self.nombre()+'"apellido":"'+self.apellido()+'"}';
		return chofer;
	};
	
	self.salvar = function() {
	};
}