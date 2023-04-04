CREATE TABLE proveedores(
	id int not null AUTO_INCREMENT,
	nombre varchar(50) not null,
	direccion varchar(240) not null,	
	correo_electronico varchar(240) not null,
	nombre_persona_contacto varchar(240) not null,
	cuenta_bancaria varchar(240) not null,
	PRIMARY KEY(id)
);