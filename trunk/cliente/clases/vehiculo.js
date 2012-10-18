function CVehiculo() {
	var self = this;
	
	self.id = ko.observable();
	self.patente = ko.observable().extend({required: true});

	self.cargar = function(jsonData) {
		console.log("inicia carga Vehiculo");
		if(jsonData) {
			self.id(jsonData.id);
			self.patente(jsonData.patente);
		}
	};
	
	self.toJson = function() {
		var vehiculo = {};
		vehiculo.id = self.id();
		vehiculo.patente = self.patente();
		
		return vehiculo;
	};

	self.toString = function() {
		var vehiculo = '{"id":"'+self.id()+'"patente":"'+self.patente()+'"}';
		return vehiculo;
	};
	
	self.salvar = function() {
	};
}