create table registro(
	id int unsigned NOT NULL AUTO_INCREMENT,
	tipo varchar(100) NOT NULL,  -- Ata/Periódico/Registro de Empregados/...
	titulo varchar(300) NOT NULL,
	datareg date,                -- Data do registro
	horareg varchar(5),			 -- hh:mm
	descricao mediumtext,
	origem varchar(200),         -- SindMusi/CMRJ/Rádio que esqueci o nome/Biblioteca Nacional/...
	relevancia int unsigned,
	primary key(id)
);

create table midia(
	id int unsigned NOT NULL AUTO_INCREMENT,
	registroid int unsigned NOT NULL,
	nome varchar(300),			 -- 'midia.jpg', por exemplo
	primary key(id),
	foreign key(registroid) references registro(id)
);
