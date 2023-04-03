CREATE TABLE usuarios(
	id int not null AUTO_INCREMENT,
	nombre_usuario varchar(50) not null,
	nombre_completo varchar(240) not null,
	correo varchar(240) not null,
	contra varchar(240) not null,
	fecha_nacimiento varchar(240) not null,
	direccion varchar(240) not null,
	numero_telerfono varchar(240) not null,
	rol_usuario varchar(240) not null,
	PRIMARY KEY(id)
);