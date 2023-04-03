CREATE TABLE productos(
	id int not null AUTO_INCREMENT,
	nombre varchar(50) not null,
	descripcion varchar(240) not null,
	codigo_barras varchar(240) not null,
	sku varchar(240) not null,
	precio double not null,
	cantidad_stock int not null,
	categoria varchar(50) not null,
	proveedor int not null,
	fecha_entrada varchar(50) not null,
	PRIMARY KEY(id),
	FOREIGN KEY(proveedor) REFERENCES proveedores(id)
);
