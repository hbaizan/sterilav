function CUsuario() {
	var self = this;
	
	self.id = ko.observable();
	self.nombre = ko.observable().extend({required: true});
	self.apellido = ko.observable().extend({required: true});
	self.usuario = ko.observable().extend({required: true});
	self.perfil = ko.observable().extend({required: true});
	self.password = ko.observable().extend({required: true});
	self.departamento = ko.observable().extend({required: true});
	self.remito = ko.observable(false).extend({required: true});
	self.activo = ko.observable(false).extend({required: true});
	self.repite = ko.observable();		
};