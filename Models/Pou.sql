USE pou;

CREATE TABLE IF NOT EXISTS usuario (
    idsu INT PRIMARY KEY AUTO_INCREMENT,
    apelido VARCHAR(30) NOT NULL,
    dindin INT DEFAULT 0,
    nivel INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tipo (
    tipid INT NOT NULL PRIMARY KEY,
    elemento VARCHAR(10)
) ENGINE=InnoDB;

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
    veloz INT,

    FOREIGN KEY (tipid) REFERENCES tipo(tipid)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS deck (
    ideck INT AUTO_INCREMENT PRIMARY KEY,
    idsu INT NOT NULL,
    idm1 INT DEFAULT NULL,
    idm2 INT DEFAULT NULL,
    idm3 INT DEFAULT NULL,
    titulo VARCHAR(12) DEFAULT 'FolhaX',
    descartado TINYINT(1) NOT NULL DEFAULT 0,

    FOREIGN KEY (idsu) REFERENCES usuario(idsu),
    FOREIGN KEY (idm1) REFERENCES monstro(id),
    FOREIGN KEY (idm2) REFERENCES monstro(id),
    FOREIGN KEY (idm3) REFERENCES monstro(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS deck_inimigo (
    iddeck_inimigo INT AUTO_INCREMENT PRIMARY KEY,
    idm1 INT NOT NULL,
    idm2 INT NOT NULL,
    idm3 INT NOT NULL,
    titulo VARCHAR(30) NOT NULL,

    FOREIGN KEY (idm1) REFERENCES monstro(id),
    FOREIGN KEY (idm2) REFERENCES monstro(id),
    FOREIGN KEY (idm3) REFERENCES monstro(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS itens (
    itid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nomi VARCHAR(20) NOT NULL,
    nefeito INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descri VARCHAR(200)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS batalha (
    idBa INT AUTO_INCREMENT PRIMARY KEY,
    ideck INT NOT NULL,
    iddeck_inimigo INT NOT NULL,
    habitat VARCHAR(50) NOT NULL,
    vencedor VARCHAR(20),
    data DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (ideck) REFERENCES deck(ideck),
    FOREIGN KEY (iddeck_inimigo)
        REFERENCES deck_inimigo(iddeck_inimigo)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS logbatalhas (
    idlog INT AUTO_INCREMENT PRIMARY KEY,
    idBa INT NOT NULL,
    rodada INT NOT NULL,
    acao TEXT NOT NULL,
    dano INT DEFAULT 0,
    itid INT DEFAULT NULL,
    idsu INT NOT NULL,

    FOREIGN KEY (idsu) REFERENCES usuario(idsu),
    FOREIGN KEY (idBa) REFERENCES batalha(idBa),
    FOREIGN KEY (itid) REFERENCES itens(itid)
) ENGINE=InnoDB;

ALTER TABLE usuario
ADD token_hash CHAR(64) NOT NULL UNIQUE;