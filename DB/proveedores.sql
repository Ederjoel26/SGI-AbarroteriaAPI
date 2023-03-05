CREATE TABLE proveedores(
	id int not null AUTO_INCREMENT,
	proveedor varchar(50),
	nombre varchar(50),
	producto varchar(50),
	precio_unitario double,
	costo_unitario double,
	numero_telefonico varchar(50),
	PRIMARY KEY(id)
);