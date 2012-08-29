Pyramid.rootPath = './cliente/';

//Set up file dependencies
Pyramid.newDependency({
    name: 'standard',
    files: [
    'librerias/jquery-1.7.2.min.js',
	 'librerias/knockout-2.1.0.js',
	 'librerias/knockout.simpleGrid.js',	 
	 'librerias/knockout.validation.v0.9.js',
	 'librerias/bootstrap.js',
	 'librerias/bootstrap-tab.js',
	 'librerias/bootstrap-collapse.js',
	 'librerias/amplify.js',
	 'librerias/sterilav.js'
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
	'objetos/movies.js',
	'objetos/actors.js',
	'Index.js'
   ],
    dependencies: ['standard','lookAndFeel']
});
