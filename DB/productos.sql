CREATE TABLE productos(
	id int not null AUTO_INCREMENT,
	producto varchar(50),
	cantidad int,
	proveedor int,
	costo_unitario double,
	PRIMARY KEY(id),
	FOREIGN KEY(proveedor) REFERENCES proveedores(id)
);
