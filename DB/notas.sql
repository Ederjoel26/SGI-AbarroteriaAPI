CREATE TABLE notas(
	id int not null AUTO_INCREMENT,
	nota varchar(240),
	fecha_limite varchar(50),
	descripcion varchar(50),
	realizado boolean,
	PRIMARY KEY(id)
);