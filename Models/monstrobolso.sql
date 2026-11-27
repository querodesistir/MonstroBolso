/*CREATE DATABASE IF NOT EXISTS monstrobolso;*/
USE monstrobolso;

CREATE TABLE IF NOT EXISTS usuario (
    idsu VARCHAR(36) NOT NULL,
    apelido VARCHAR(30) NOT NULL,
    dindin INT DEFAULT 0,
    nivel INT DEFAULT 0,
    token_hash CHAR(64) NOT NULL,
    PRIMARY KEY (idsu),
    UNIQUE KEY (token_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tipo (
    tipid VARCHAR(36) NOT NULL,
    elemento VARCHAR(10),
    PRIMARY KEY (tipid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS monstro (
    id VARCHAR(36) NOT NULL,
    nome VARCHAR(30) NOT NULL UNIQUE,
    imagem VARCHAR(255) NOT NULL,
    dano INT NOT NULL,
    hp INT DEFAULT 100,
    ahp INT,
    `def` INT,
    prio INT,
    danoEspecial INT,
    tipid VARCHAR(36) NOT NULL,
    veloz INT,
    PRIMARY KEY (id),
    FOREIGN KEY (tipid) REFERENCES tipo(tipid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS deck (
    ideck VARCHAR(36) NOT NULL,
    idsu VARCHAR(36) NOT NULL,
    idm1 VARCHAR(36) DEFAULT NULL,
    idm2 VARCHAR(36) DEFAULT NULL,
    idm3 VARCHAR(36) DEFAULT NULL,
    titulo VARCHAR(12) DEFAULT 'FolhaX',
    descartado TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (ideck),
    FOREIGN KEY (idsu) REFERENCES usuario(idsu),
    FOREIGN KEY (idm1) REFERENCES monstro(id),
    FOREIGN KEY (idm2) REFERENCES monstro(id),
    FOREIGN KEY (idm3) REFERENCES monstro(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS deck_inimigo (
    iddeck_inimigo VARCHAR(36) NOT NULL,
    idm1 VARCHAR(36) NOT NULL,
    idm2 VARCHAR(36) NOT NULL,
    idm3 VARCHAR(36) NOT NULL,
    titulo VARCHAR(30) NOT NULL,
    PRIMARY KEY (iddeck_inimigo),
    FOREIGN KEY (idm1) REFERENCES monstro(id),
    FOREIGN KEY (idm2) REFERENCES monstro(id),
    FOREIGN KEY (idm3) REFERENCES monstro(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS itens (
    itid VARCHAR(36) NOT NULL,
    nomi VARCHAR(20) NOT NULL,
    nefeito INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descri VARCHAR(200),
    PRIMARY KEY (itid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS batalha (
    idBa VARCHAR(36) NOT NULL,
    ideck VARCHAR(36) NOT NULL,
    iddeck_inimigo VARCHAR(36) NOT NULL,
    habitat VARCHAR(50) NOT NULL,
    vencedor VARCHAR(20),
    `data` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (idBa),
    FOREIGN KEY (ideck) REFERENCES deck(ideck),
    FOREIGN KEY (iddeck_inimigo) REFERENCES deck_inimigo(iddeck_inimigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS logbatalhas (
    idlog VARCHAR(36) NOT NULL,
    idBa VARCHAR(36) NOT NULL,
    rodada INT NOT NULL,
    acao TEXT NOT NULL,
    dano INT DEFAULT 0,
    itid VARCHAR(36) DEFAULT NULL,
    idsu VARCHAR(36) NOT NULL,
    PRIMARY KEY (idlog),
    FOREIGN KEY (idsu) REFERENCES usuario(idsu),
    FOREIGN KEY (idBa) REFERENCES batalha(idBa),
    FOREIGN KEY (itid) REFERENCES itens(itid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO itens (itid, nomi, nefeito, preco, descri) VALUES
('item_potion_01', 'Potion', 1, 10.00, 'Recupera 20 HP'),
('item_super_potion_02', 'Super Potion', 2, 20.00, 'Recupera 50 HP'),
('item_hyper_potion_03', 'Hyper Potion', 3, 50.00, 'Recupera 200 HP'),
('item_max_potion_04', 'Max Potion', 4, 80.00, 'Recupera todo o HP'),
('item_full_restore_05', 'Full Restore', 5, 100.00, 'Recupera todo o HP e cura status'),
('item_antidote_06', 'Antidote', 6, 15.00, 'Cura envenenamento'),
('item_burn_heal_07', 'Burn Heal', 7, 15.00, 'Cura queimadura'),
('item_ice_heal_08', 'Ice Heal', 8, 15.00, 'Cura congelamento'),
('item_awakening_09', 'Awakening', 9, 15.00, 'Cura sono'),
('item_paralyze_heal_10', 'Paralyze Heal', 10, 15.00, 'Cura paralisia'),
('item_full_heal_11', 'Full Heal', 11, 30.00, 'Cura todos os status'),
('item_revive_12', 'Revive', 12, 50.00, 'Revive um monstro com metade do HP');

INSERT IGNORE INTO tipo (tipid, elemento) VALUES
('tipo_agua_01', 'agua'),
('tipo_fogo_02', 'fogo'),
('tipo_terra_03', 'terra'),
('tipo_ar_04', 'ar'),
('tipo_eletric_05', 'eletricidade'),
('tipo_gelo_06', 'gelo'),
('tipo_sombra_07', 'sombra');

INSERT IGNORE INTO monstro
(id, nome, imagem, dano, hp, ahp, `def`, prio, danoEspecial, tipid, veloz)
VALUES
('mon_01', 'Fulgorraposa', 'Fulgorraposa.png', 30, 600, 600, 35, 3, 80, 'tipo_fogo_02', 90),
('mon_02', 'Aquaturto', 'Aquaturto.png', 25, 700, 700, 50, 2, 70, 'tipo_agua_01', 55),
('mon_03', 'Marevoraz', 'Marevoraz.png', 40, 800, 800, 40, 4, 95, 'tipo_agua_01', 80),
('mon_04', 'Folhassauro', 'Folhassauro.png', 35, 650, 650, 45, 3, 75, 'tipo_terra_03', 85),
('mon_05', 'Magmarrapo', 'Magmarrapo.png', 45, 750, 750, 35, 4, 100, 'tipo_fogo_02', 95),
('mon_06', 'Floraspinho', 'Floraspinho.png', 30, 800, 800, 55, 2, 70, 'tipo_terra_03', 40),
('mon_07', 'Voltalupus', 'Voltalupus.png', 50, 650, 650, 30, 5, 100, 'tipo_eletric_05', 100),
('mon_08', 'Trovapedra', 'Trovapedra.png', 35, 900, 900, 60, 3, 90, 'tipo_eletric_05', 45),
('mon_09', 'Cristalobo', 'Cristalobo.png', 40, 850, 850, 55, 3, 100, 'tipo_gelo_06', 60),
('mon_10', 'Noctiflama', 'Noctiflama.png', 45, 700, 700, 35, 5, 95, 'tipo_sombra_07', 90),
('mon_11', 'Umbrafel', 'Umbrafel.png', 50, 800, 800, 40, 5, 100, 'tipo_sombra_07', 85),
('mon_12', 'Ventodraco', 'Ventodraco.png', 40, 900, 900, 45, 4, 90, 'tipo_ar_04', 90),
('mon_13', 'Rochatouro', 'Rochatouro.png', 30, 950, 950, 60, 1, 65, 'tipo_terra_03', 30),
('mon_14', 'Granitauro', 'Granitauro.png', 25, 1000, 1000, 60, 1, 60, 'tipo_terra_03', 25),
('mon_15', 'Glaciadraco', 'Glaciadraco.png', 50, 850, 850, 45, 4, 100, 'tipo_gelo_06', 95),
('mon_16', 'Cristalume', 'Cristalume.png', 35, 750, 750, 50, 3, 85, 'tipo_gelo_06', 65);

INSERT IGNORE INTO deck_inimigo (iddeck_inimigo, idm1, idm2, idm3, titulo)
VALUES ('deck_ini_01', 'mon_05', 'mon_07', 'mon_11', 'Equipe teste');

SELECT COUNT(*) FROM tipo;
SELECT COUNT(*) FROM itens;
SELECT COUNT(*) FROM monstro;
SELECT COUNT(*) FROM deck_inimigo;