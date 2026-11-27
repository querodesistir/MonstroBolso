<?php
/* ============================================================
   DADOS DO JOGO MONSTROS (PHP)
   tudo que o JS tinha em tabelas, agora em arrays do PHP:
   monstros, tipos (tabela de eficacia, cores, nomes, biomas),
   itens da loja e condicoes de status
   ============================================================ */

/* sprites e imagens de bioma ficam em dados_sprites.php (colados em base64) */
$SPRITES = require __DIR__ . '/dados_sprites.php';
$FIGURAS   = $SPRITES['figuras'];
$BIOMAS_IMG = $SPRITES['biomas'];
$IMG_ESCOLHA = $SPRITES['escolha'];
$IMG_FLORESTA = $SPRITES['floresta'];

/* ---------- monstros (mesmos stats do jogo original) ---------- */
$MONSTROS = [
  ['nome' => 'Flamigato', 'img' => 'flamigato', 'tipo' => 'fogo', 'hp' => 78, 'atk' => 16, 'def' => 9, 'inicial' => true, 'frase' => 'Aquece tudo que encosta', 'golpe' => 'Garra Flamejante', 'poder' => 21],
  ['nome' => 'Aquatopo', 'img' => 'aquatopo', 'tipo' => 'agua', 'hp' => 85, 'atk' => 13, 'def' => 12, 'inicial' => true, 'frase' => 'Calmo, mas nunca desiste', 'golpe' => 'Jato de Lago', 'poder' => 19],
  ['nome' => 'Folhaco', 'img' => 'folhaco', 'tipo' => 'planta', 'hp' => 82, 'atk' => 14, 'def' => 11, 'inicial' => true, 'frase' => 'Cresce mais que todo mundo', 'golpe' => 'Chicote Trevo', 'poder' => 18],
  ['nome' => 'Vulcabra', 'img' => 'vulcabra', 'tipo' => 'fogo', 'hp' => 74, 'atk' => 17, 'def' => 8, 'frase' => null, 'golpe' => 'Asas de Brasa', 'poder' => 22],
  ['nome' => 'Mareman', 'img' => 'mareman', 'tipo' => 'agua', 'hp' => 80, 'atk' => 15, 'def' => 10, 'frase' => null, 'golpe' => 'Onda Marina', 'poder' => 20],
  ['nome' => 'Cactopun', 'img' => 'cactopun', 'tipo' => 'planta', 'hp' => 76, 'atk' => 15, 'def' => 12, 'frase' => null, 'golpe' => 'Espinho Buena', 'poder' => 20],
  ['nome' => 'Zapelé', 'img' => 'zapele', 'tipo' => 'eletrico', 'hp' => 76, 'atk' => 16, 'def' => 9, 'frase' => null, 'golpe' => 'Rabo de Trovão', 'poder' => 21],
  ['nome' => 'Voltaruga', 'img' => 'voltaruga', 'tipo' => 'eletrico', 'hp' => 88, 'atk' => 12, 'def' => 13, 'frase' => null, 'golpe' => 'Casco Chocante', 'poder' => 18],
  ['nome' => 'Rochambo', 'img' => 'rochambo', 'tipo' => 'pedra', 'hp' => 84, 'atk' => 15, 'def' => 12, 'frase' => null, 'golpe' => 'Punho Rochoso', 'poder' => 20],
  ['nome' => 'Granito', 'img' => 'granito', 'tipo' => 'pedra', 'hp' => 92, 'atk' => 11, 'def' => 15, 'frase' => null, 'golpe' => 'Avalanche', 'poder' => 17],
  ['nome' => 'Cristalino', 'img' => 'cristalino', 'tipo' => 'gelo', 'hp' => 80, 'atk' => 14, 'def' => 11, 'frase' => null, 'golpe' => 'Sopro Polar', 'poder' => 19],
  ['nome' => 'Nevasca', 'img' => 'nevasca', 'tipo' => 'gelo', 'hp' => 78, 'atk' => 15, 'def' => 10, 'frase' => null, 'golpe' => 'Tempestade Branca', 'poder' => 21],
  ['nome' => 'Umbrino', 'img' => 'umbrino', 'tipo' => 'sombra', 'hp' => 74, 'atk' => 17, 'def' => 8, 'frase' => null, 'golpe' => 'Toque Espectral', 'poder' => 21],
  ['nome' => 'Noturnix', 'img' => 'noturnix', 'tipo' => 'sombra', 'hp' => 76, 'atk' => 16, 'def' => 9, 'frase' => null, 'golpe' => 'Olhar da Noite', 'poder' => 20],
  ['nome' => 'Draguino', 'img' => 'draguino', 'tipo' => 'dragao', 'hp' => 86, 'atk' => 16, 'def' => 11, 'frase' => null, 'golpe' => 'Fúria Ancestral', 'poder' => 22],
  ['nome' => 'Lunarix', 'img' => 'lunarix', 'tipo' => 'lua', 'hp' => 82, 'atk' => 17, 'def' => 11, 'frase' => 'Brilha no luar e guia os perdidos', 'golpe' => 'Feixe Lunar', 'poder' => 22],
  ['nome' => 'Megacacto', 'img' => 'megacacto', 'tipo' => 'planta', 'hp' => 86, 'atk' => 15, 'def' => 13, 'frase' => 'Espinhos, flores e muita paciência', 'golpe' => 'Chuva de Espinhos', 'poder' => 20],
  ['nome' => 'Glacius', 'img' => 'glacius', 'tipo' => 'gelo', 'hp' => 84, 'atk' => 16, 'def' => 12, 'frase' => 'Nasceu numa nevasca eterna', 'golpe' => 'Lança de Cristal', 'poder' => 21],
  ['nome' => 'Fadalux', 'img' => 'fadalux', 'tipo' => 'fada', 'hp' => 80, 'atk' => 15, 'def' => 13, 'inicial' => true, 'selvagem' => true, 'frase' => 'Cristais rosas cheios de magia antiga', 'golpe' => 'Cintilância', 'poder' => 20],
  ['nome' => 'Lutalux', 'img' => 'lutalux', 'tipo' => 'lutador', 'hp' => 88, 'atk' => 17, 'def' => 11, 'inicial' => true, 'selvagem' => true, 'frase' => 'Luta com o coração e punhos de pedra', 'golpe' => 'Punho Cometa', 'poder' => 22],
];

/* ---------- tipos ---------- */
/* contra quem cada ataque é super efetivo (x1.5) */
$EFICAZ = [
  'fogo' => ['planta', 'gelo', 'lua'], 'agua' => ['fogo', 'pedra', 'terra'], 'planta' => ['agua', 'pedra', 'terra'],
  'eletrico' => ['agua', 'gelo', 'terra'], 'pedra' => ['fogo', 'eletrico', 'lua'], 'gelo' => ['planta', 'dragao', 'terra'],
  'sombra' => ['dragao', 'eletrico'], 'dragao' => ['dragao', 'pedra'],
  'lua' => ['sombra', 'agua'], 'terra' => ['fogo', 'eletrico'], 'fada' => ['dragao', 'sombra', 'lutador'],
  'lutador' => ['pedra', 'gelo'], 'neutro' => []
];
/* contra quem cada ataque é fraco (x0.6) */
$FRACO = [
  'fogo' => ['agua', 'pedra', 'terra'], 'agua' => ['planta', 'eletrico', 'lua'], 'planta' => ['fogo', 'gelo'],
  'eletrico' => ['pedra', 'sombra', 'terra'], 'pedra' => ['agua', 'planta', 'dragao'], 'gelo' => ['fogo', 'eletrico'],
  'sombra' => ['sombra', 'dragao', 'lua', 'fada'], 'dragao' => ['gelo', 'sombra', 'fada'],
  'lua' => ['fogo', 'pedra'], 'terra' => ['agua', 'planta', 'gelo'], 'fada' => ['fogo', 'pedra'],
  'lutador' => ['fada', 'lua'], 'neutro' => []
];
$COR_TIPO = [
  'fogo' => '#e2582c', 'agua' => '#2c7de2', 'planta' => '#2ca24a', 'eletrico' => '#c99a10',
  'pedra' => '#8a6d4b', 'gelo' => '#59b7d4', 'sombra' => '#5b4b8a', 'dragao' => '#6f35c9',
  'lua' => '#8f7fd3', 'terra' => '#b5722e', 'fada' => '#ee8ac2', 'lutador' => '#8f4a1f', 'neutro' => '#7a7a7a'
];
$NOME_TIPO = [
  'fogo' => 'Fogo', 'agua' => 'Água', 'planta' => 'Planta', 'eletrico' => 'Elétrico',
  'pedra' => 'Pedra', 'gelo' => 'Gelo', 'sombra' => 'Sombra', 'dragao' => 'Dragão',
  'lua' => 'Lua', 'terra' => 'Terra', 'fada' => 'Fada', 'lutador' => 'Lutador', 'neutro' => 'Normal'
];
$NOME_BIOMA = [
  'fogo' => 'Vulcão', 'agua' => 'Lago Azul', 'planta' => 'Floresta Verdejante', 'eletrico' => 'Campo de Trovões',
  'pedra' => 'Cânion de Pedra', 'gelo' => 'Geleira', 'sombra' => 'Floresta Sombria', 'dragao' => 'Cume do Dragão',
  'lua' => 'Clareira Lunar', 'terra' => 'Montanha Rochosa', 'fada' => 'Bosque Encantado', 'lutador' => 'Dojo Ancestral', 'neutro' => 'Campo Aberto'
];

/* ---------- condicoes de status ---------- */
/* cada tipo de golpe pode aplicar um status com 15% de chance ao acertar */
$STATUS_DO_TIPO = ['fogo' => 'queimadura', 'gelo' => 'congelado', 'eletrico' => 'paralisia', 'planta' => 'sono', 'sombra' => 'veneno', 'fada' => 'sono'];
$NOMES_STATUS = ['veneno' => 'envenenado', 'queimadura' => 'queimado', 'congelado' => 'congelado', 'sono' => 'dormindo', 'paralisia' => 'paralisado'];
$TAGS_STATUS = ['veneno' => 'PSN', 'queimadura' => 'BRN', 'congelado' => 'FRZ', 'sono' => 'SLP', 'paralisia' => 'PAR'];
$CORES_STATUS = ['veneno' => '#a04ad8', 'queimadura' => '#d8623a', 'congelado' => '#4fb8e0', 'sono' => '#9a8a55', 'paralisia' => '#c8b020'];
$MSG_STATUS = [
  'veneno' => ' foi envenenado!',
  'queimadura' => ' sofreu uma queimadura!',
  'congelado' => ' foi congelado!',
  'sono' => ' caiu no sono!',
  'paralisia' => ' ficou paralisado!'
];

/* ---------- itens da loja ---------- */
/* grupo hp = restauradores de HP | status = cura de condicao | outros/casa = especiais
   'cura' => 'hp' recupera o valor fixo; 'total' recupera 100%;
   'statusTodos' cura qualquer status junto; 'curaStatus' cura aquela condicao */
$ITENS = [
  ['chave' => 'potion',       'nome' => 'Potion',        'preco' => 10, 'desc' => 'Recupera 20 HP',                    'grupo' => 'hp',     'cura' => 'hp', 'hpfixo' => 20],
  ['chave' => 'super',        'nome' => 'Super Potion',  'preco' => 20, 'desc' => 'Recupera 50 HP',                    'grupo' => 'hp',     'cura' => 'hp', 'hpfixo' => 50],
  ['chave' => 'hyper',        'nome' => 'Hyper Potion',  'preco' => 35, 'desc' => 'Recupera 200 HP',                   'grupo' => 'hp',     'cura' => 'hp', 'hpfixo' => 200],
  ['chave' => 'max',          'nome' => 'Max Potion',    'preco' => 45, 'desc' => 'Recupera 100% do HP',               'grupo' => 'hp',     'cura' => 'total'],
  ['chave' => 'fullrestore',  'nome' => 'Full Restore',  'preco' => 55, 'desc' => '100% do HP e cura qualquer status', 'grupo' => 'hp',     'cura' => 'total', 'statusTodos' => true],
  ['chave' => 'antidote',     'nome' => 'Antidote',      'preco' => 5,  'desc' => 'Cura envenenamento',                'grupo' => 'status', 'curaStatus' => 'veneno'],
  ['chave' => 'burnheal',     'nome' => 'Burn Heal',     'preco' => 5,  'desc' => 'Cura queimadura',                   'grupo' => 'status', 'curaStatus' => 'queimadura'],
  ['chave' => 'iceheal',      'nome' => 'Ice Heal',      'preco' => 5,  'desc' => 'Cura congelamento',                 'grupo' => 'status', 'curaStatus' => 'congelado'],
  ['chave' => 'awakening',    'nome' => 'Awakening',     'preco' => 5,  'desc' => 'Acorda um monstro dormindo',        'grupo' => 'status', 'curaStatus' => 'sono'],
  ['chave' => 'paralyzeheal', 'nome' => 'Paralyze Heal', 'preco' => 5,  'desc' => 'Cura paralisia',                    'grupo' => 'status', 'curaStatus' => 'paralisia'],
  ['chave' => 'fullheal',     'nome' => 'Full Heal',     'preco' => 10, 'desc' => 'Cura todos os problemas de status', 'grupo' => 'status', 'curaStatus' => 'todos'],
  ['chave' => 'reviver',      'nome' => 'Reviver',       'preco' => 60, 'desc' => 'Revive o parceiro desmaiado com metade do HP', 'grupo' => 'outros'],
  ['chave' => 'pocoes',       'nome' => 'Poção de Cura', 'preco' => 10, 'desc' => 'Recupera 35% do HP',                'grupo' => 'casa'],
  ['chave' => 'forca',        'nome' => 'Poção de Força', 'preco' => 18, 'desc' => '+25% de ataque na batalha',        'grupo' => 'casa'],
  ['chave' => 'escudo',       'nome' => 'Poção de Escudo', 'preco' => 15, 'desc' => '+30% de defesa na batalha',       'grupo' => 'casa'],
];
$GRUPOS_ITENS = [
  ['id' => 'hp',     'titulo' => 'Restauradores de HP'],
  ['id' => 'status', 'titulo' => 'Cura de Condição'],
  ['id' => 'outros', 'titulo' => 'Itens especiais'],
  ['id' => 'casa',   'titulo' => 'Poções da casa'],
];

/* estoque inicial: a Poção de Cura da casa ja vem com 2, o resto se compra */
function estoquePadrao() {
  $e = [];
  global $ITENS;
  foreach ($ITENS as $item) {
    $e[$item['chave']] = ($item['chave'] === 'pocoes') ? 2 : 0;
  }
  return $e;
}

function buscarItem($chave) {
  global $ITENS;
  foreach ($ITENS as $item) {
    if ($item['chave'] === $chave) return $item;
  }
  return null;
}
