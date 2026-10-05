USE pou;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS loja;
DROP TABLE IF EXISTS itens;
DROP TABLE IF EXISTS DeckBattle;
DROP TABLE IF EXISTS monstro;
DROP TABLE IF EXISTS especie;
DROP TABLE IF EXISTS tipo;
DROP TABLE IF EXISTS usuario;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE usuario (
    mail VARCHAR(100) PRIMARY KEY NOT NULL,
    senha VARCHAR(12) NOT NULL,
    apelido varchar(30) not null,
    nivel INT DEFAUlt 0
);

create table tipo(
	tipid int not null primary key,
	elemento varchar(10)
);

create table especie(
	estpid int not null primary key,
	esper varchar(12) not null
);

create table monstro (
	id int not null primary key,
	nome varchar(30) not null,
	dano int not null,
	hp int not null,
	def int,
	estpid int not null,
	tipid int not null,
foreign key (estpid) references especie(estpid),
foreign key (tipid) references tipo(tipid)
);

create table DeckBattle(
idm1 int not null,
idm2 int not null,
idm3 int not null,
titulo varchar(12) default 'FolhaX',
foreign key (idm1) references monstro (id),
foreign key (idm2) references monstro (id),
foreign key (idm2) references monstro (id),
);

create table itens(
itid int not null primary key,
nomi varchar(12) not null,
nefeito int not null,
descri varchar(200)
);

create table loja(
itid int not null,
preco decimal not null,
foreign key (itid) references (itid)

);
