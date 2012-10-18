Pyramid.rootPath = './cliente/';

//Set up file dependencies
Pyramid.newDependency({
    name: 'standard',
    files: [
    'librerias/jquery-1.8.2.js',
	'librerias/jquery-ui.js',
	'librerias/knockout-2.1.0.js',
	'librerias/knockout.simpleGrid.js',	 
	'librerias/knockout.validation.v0.9.js',
	'librerias/bootstrap.js',
	'librerias/bootstrap-tab.js',
	'librerias/bootstrap-collapse.js',
	'librerias/bootstrap-tooltip.js',
	'librerias/amplify.js',
	'librerias/sterilav.js',
	'librerias/jquery.PrintArea.js'
    ]
});

Pyramid.newDependency({
name:'lookAndFeel',
files: [
	'estilos/bootstrap.css',
	'estilos/bootstrap-responsive.css'
    ]
});

Pyramid.newDependency({
name:'main',
files: [
	'Index.js',
	'clases/vehiculo.js',
	'clases/deposito.js',
	'clases/empresa.js',	
	'clases/chofer.js',
	'clases/remito.js',	
	'clases/usuario.js'	
   ],
    dependencies: ['standard','lookAndFeel']
});
