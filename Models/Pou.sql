USE pou;

CREATE TABLE IF NOT EXISTS usuario (
	idsu int PRIMARY KEY not null,
    mail VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(12) NOT NULL,
    apelido varchar(30) not null,
	dindin int default 10,
    nivel INT DEFAUlt 0
);

create table IF NOT EXISTS tipo(
	tipid int not null primary key,
	elemento varchar(10)
);

create table IF NOT EXISTS monstro (
	id int not null primary key,
	nome varchar(30) not null UNIQUE,
	dano int not null,
	hp int default 100,
	ahp int,
	def int,
	prio int,
    danoEspecial int,
	tipid int not null,
	nodeck boolean default false,
foreign key (tipid) references tipo(tipid)
);

create table IF NOT EXISTS deckbattle(
ideck int PRIMARY KEY,
idm1 int not null,
idm2 int not null,
idm3 int not null,
titulo varchar(12) default 'FolhaX',
foreign key (idm1) references monstro (id),
foreign key (idm2) references monstro (id),
foreign key (idm3) references monstro (id)
);

create table IF NOT EXISTS itens(
itid int not null primary key,
nomi varchar(20) not null,
nefeito int not null,
preco decimal not null,
descri varchar(200)
);

CREATE TABLE IF NOT EXISTS logbatalhas(
idlog int primary key,
ideck int,
dano INT,
cura INT,
foreign KEY (ideck) references deckbattle(ideck)
);

INSERT ignore INTO TIPO(tipid, elemento)
VALUES(3, "aquatico");
INSERT ignore INTO TIPO(tipid, elemento)
VALUES(1, "voador");

INSERT ignore INTO monstro(id, nome, dano, hp, ahp, def, prio, danoEspecial, tipid) 
VALUES(4, "Fogi", 5, 100, 100, 1, 20, 3, 3);
INSERT IGNORE INTO monstro(id, nome, dano, hp, ahp, def, prio, danoEspecial, tipid)
VALUES(2, "Quero-quero", 10, 100, 100, 1, 30, 1, 1);