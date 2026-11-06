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
	idsu int PRIMARY KEY not null,
    mail VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(12) NOT NULL,
    apelido varchar(30) not null,
	dindin int default 10,
    nivel INT DEFAUlt 0
);

create table tipo(
	tipid int not null primary key,
	elemento varchar(10)
);

create table monstro (
	id int not null primary key,
	nome varchar(30) not null UNIQUE,
	dano int not null,
	hp int default 100,
	ahp int,
	def int,
	prio int,
    danoEspecial int,
	tipid int not null,
foreign key (estpid) references especie(estpid),
foreign key (tipid) references tipo(tipid)
);

create table deckbattle(
ideck int PRIMARY KEY
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
preco decimal not null,
descri varchar(200)
);

CREATE TABLE logbatalhas(
idlog int primary key,
ideck int,
dano INT,
cura INT,
foreign KEY (ideck) references deckbattle(ideck)
);

INSERT INTO TIPO(tipid, elemento);
VALUES(2, "aquatico");
INSERT INTO TIPO(tipid, elemento);
VALUES(1, "voador");

INSERT INTO monstro(id, nome, dano, hp, ahp, def, prio, danoEspecial, tipid)
VALUES(01, "Fogi", 5, 100, 100, 1, 20, 2);
INSERT INTO monstro(id, nome, dano, hp, ahp, def, prio, danoEspecial, tipid)
VALUES(01, "Quero-quero", 10, 100, 100, 1, 30, 1);