USE pou;

CREATE TABLE IF NOT EXISTS usuario (
    idsu INT PRIMARY KEY AUTO_INCREMENT,
    mail VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    apelido VARCHAR(30) NOT NULL,
    dindin INT DEFAULT 0,
    nivel INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS tipo (
    tipid INT NOT NULL PRIMARY KEY,
    elemento VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS monstro (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL UNIQUE,
    imagem VARCHAR(255) NOT NULL,
    dano INT NOT NULL,
    hp INT DEFAULT 100,
    ahp INT,
    def INT,
    prio INT,
    danoEspecial INT,
    tipid INT NOT NULL,
    nodeck BOOLEAN,

    FOREIGN KEY (tipid) REFERENCES tipo(tipid)
);

CREATE TABLE IF NOT EXISTS deck (
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

CREATE TABLE IF NOT EXISTS itens (
    itid INT NOT NULL PRIMARY KEY,
    nomi VARCHAR(20) NOT NULL,
    nefeito INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descri VARCHAR(200)
);


CREATE TABLE IF NOT EXISTS logbatalhas (
    idlog INT PRIMARY KEY,
    ideckMau INT,
    ideck INT,
    dano INT,
    cura INT,

    FOREIGN KEY (ideck) REFERENCES deckbattle(ideck),
    FOREIGN KEY (ideckMau) REFERENCES deckbattle(ideck)
);

INSERT IGNORE INTO tipo(tipid, elemento)
VALUES (3, 'aquatico');

INSERT IGNORE INTO tipo(tipid, elemento)
VALUES (1, 'voador');

INSERT IGNORE INTO monstro(id, nome, imagem, dano, hp, ahp, def,  prio, danoEspecial, tipid, nodeck)
VALUES (4, 'Fogi', 'fogi.png', 5, 100, 100, 1,20, 3, 3, TRUE);

INSERT IGNORE INTO monstro(id, nome, imagem, dano, hp, ahp, def, prio, danoEspecial, tipid, nodeck)
VALUES ( 2, 'Quero-quero', 'quero.png', 10, 100, 100, 1, 30, 1, 1, FALSE);