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
    veloz int,
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

CREATE TABLE batalha (
    idBa INT AUTO_INCREMENT PRIMARY KEY,
    ideck INT NOT NULL,
    deckMau INT NOT NULL,
    habitat VARCHAR(50) NOT NULL,
    vencedor VARCHAR(20),
    data DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (ideck) REFERENCES deck(ideck),
    FOREIGN KEY (deckMau) REFERENCES deck(ideck)
);

CREATE TABLE IF NOT EXISTS logbatalhas (
    idlog INT AUTO_INCREMENT PRIMARY KEY,
    idBa INT NOT NULL,
    rodada INT NOT NULL,
    acao TEXT NOT NULL,
    dano INT DEFAULT 0,
    itid INT DEFAULT NULL,
    idsu INT NOT NULL,
    FOREIGN KEY (idsu) REFERENCES usuario(idsu)
    FOREIGN KEY (idBa) REFERENCES batalha(idBa),
    FOREIGN KEY (itid) REFERENCES itens(itid)
);