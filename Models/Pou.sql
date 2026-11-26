USE pou;
SHOW CREATE TABLE monstro;
CREATE TABLE IF NOT EXISTS usuario (
    idsu INT PRIMARY KEY AUTO_INCREMENT,
    apelido VARCHAR(30) NOT NULL,
    dindin INT DEFAULT 0,
    nivel INT DEFAULT 0,
    token_hash CHAR(64) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tipo (
    tipid INT NOT NULL PRIMARY KEY,
    elemento VARCHAR(10)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS monstro (
    id INT PRIMARY KEY,
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
    itid INT NOT NULL PRIMARY KEY,
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


INSERT IGNORE INTO itens (itid, nomi, nefeito, preco, descri) VALUES
(1, 'Potion', 1, 10.00, 'Recupera 20 pontos de HP.'),
(2, 'Super Potion', 2, 20.00, 'Recupera 50 pontos de HP.'),
(3, 'Hyper Potion', 3, 40.00, 'Recupera 200 pontos de HP.'),
(4, 'Max Potion', 4, 60.00, 'Recupera 100% do HP.'),
(5, 'Full Restore', 5, 80.00, 'Recupera 100% do HP e cura todas as condições de status.'),
(6, 'Antidote', 6, 10.00, 'Cura envenenamento.'),
(7, 'Burn Heal', 7, 10.00, 'Cura queimadura.'),
(8, 'Ice Heal', 8, 10.00, 'Cura congelamento.'),
(9, 'Awakening', 9, 10.00, 'Cura sono.'),
(10, 'Paralyze Heal', 10, 10.00, 'Cura paralisia.'),
(11, 'Full Heal', 11, 30.00, 'Cura todas as condições de status.'),
(12, 'Revive', 12, 50.00, 'Revive um monstro desmaiado com 50% do HP.');

ALTER TABLE tipo MODIFY elemento VARCHAR(20);

INSERT IGNORE INTO tipo (tipid, elemento) VALUES
(1, 'agua'),
(2, 'fogo'),
(3, 'terra'),
(4, 'ar'),
(5, 'eletricidade'),
(6, 'gelo'),
(7, 'sombra');

INSERT	IGNORE INTO monstro
(id, nome, imagem, dano, hp, ahp, `def`, prio, danoEspecial, tipid, veloz)
VALUES

(1, 'Fulgorraposa', 'Fulgorraposa.png', 30, 600, 600, 35, 3, 80, 2, 90),

(2, 'Aquaturto', 'Aquaturto.png', 25, 700, 700, 50, 2, 70, 1, 55),

(3, 'Marevoraz', 'Marevoraz.png', 40, 800, 800, 40, 4, 95, 1, 80),

(4, 'Folhassauro', 'Folhassauro.png', 35, 650, 650, 45, 3, 75, 3, 85),

(5, 'Magmarrapo', 'Magmarrapo.png', 45, 750, 750, 35, 4, 100, 2, 95),

(6, 'Floraspinho', 'Floraspinho.png', 30, 800, 800, 55, 2, 70, 3, 40),

(7, 'Voltalupus', 'Voltalupus.png', 50, 650, 650, 30, 5, 100, 5, 100),

(8, 'Trovapedra', 'Trovapedra.png', 35, 900, 900, 60, 3, 90, 5, 45),

(9, 'Cristalobo', 'Cristalobo.png', 40, 850, 850, 55, 3, 100, 6, 60),

(10, 'Noctiflama', 'Noctiflama.png', 45, 700, 700, 35, 5, 95, 7, 90),

(11, 'Umbrafel', 'Umbrafel.png', 50, 800, 800, 40, 5, 100, 7, 85),

(12, 'Ventodraco', 'Ventodraco.png', 40, 900, 900, 45, 4, 90, 4, 90),

(13, 'Rochatouro', 'Rochatouro.png', 30, 950, 950, 60, 1, 65, 3, 30),

(14, 'Granitauro', 'Granitauro.png', 25, 1000, 1000, 60, 1, 60, 3, 25),

(15, 'Glaciadraco', 'Glaciadraco.png', 50, 850, 850, 45, 4, 100, 6, 95),

(16, 'Cristalume', 'Cristalume.png', 35, 750, 750, 50, 3, 85, 6, 65);