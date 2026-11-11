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
    camimage varchar(255) not null,
	dano int not null,
	hp int default 100,
	ahp int,
	def int,
	prio int,
    danoEspecial int,
	tipid int not null,
	nodeck boolean,
foreign key (tipid) references tipo(tipid)
);

create table IF NOT EXISTS deckbattle(
    ideck INT PRIMARY KEY,
    idsu INT NOT NULL,
    idm1 INT,
    idm2 INT,
    idm3 INT,
    titulo VARCHAR(12) DEFAULT 'FolhaX',

    FOREIGN KEY (idsu) REFERENCES usuario(idsu),
    FOREIGN KEY (idm1) REFERENCES monstro(id),
    FOREIGN KEY (idm2) REFERENCES monstro(id),
    FOREIGN KEY (idm3) REFERENCES monstro(id)
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
ideckMau,
ideck int,
dano INT,
cura INT,
foreign KEY (ideck) references deckbattle(ideck),
foreign key (ideckMau) references deckbattle(ideck)
);

INSERT ignore INTO TIPO(tipid, elemento)
VALUES(3, "aquatico");
INSERT ignore INTO TIPO(tipid, elemento)
VALUES(1, "voador");

INSERT ignore INTO monstro(id, nome, dano, hp, ahp, def, prio, danoEspecial, tipid) 
VALUES(4, "Fogi", 5, 100, 100, 1, 20, 3, 3);
INSERT IGNORE INTO monstro(id, nome, dano, hp, ahp, def, prio, danoEspecial, tipid)
VALUES(2, "Quero-quero", 10, 100, 100, 1, 30, 1, 1);