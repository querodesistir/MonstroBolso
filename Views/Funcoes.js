<?php
/* ============================================================
   FUNCOES DO JOGO MONSTROS (PHP puro, sem JavaScript)
   a batalha roda por turnos: cada clique é um POST que o PHP
   processa (dano, status, turno do inimigo) e remonta a página.
   o estado inteiro mora na sessao ($_SESSION), igual ao
   localStorage da versao antiga.
   ============================================================ */

require_once __DIR__ . '/dados.php';

/* ---------- sessao ---------- */
function sessao() {
  if (session_status() === PHP_SESSION_NONE) session_start();
  if (!isset($_SESSION['moedas'])) $_SESSION['moedas'] = 30;
  if (!isset($_SESSION['estoque'])) $_SESSION['estoque'] = estoquePadrao();
  if (!isset($_SESSION['historico'])) $_SESSION['historico'] = [];
  if (!isset($_SESSION['log'])) $_SESSION['log'] = [];
  if (!isset($_SESSION['fx'])) $_SESSION['fx'] = [];
  if (!isset($_SESSION['recorde'])) $_SESSION['recorde'] = 0;
  if (!isset($_SESSION['vitorias'])) $_SESSION['vitorias'] = 0;
  if (!isset($_SESSION['msg'])) $_SESSION['msg'] = null;
  if (!isset($_SESSION['resultado'])) $_SESSION['resultado'] = null;
  // jogador e inimigo ficam vazios ate escolher parceiro
}

/* mensagem que aparece uma vez so (some depois de mostrar) */
function msg($texto, $tipo = '') {
  $_SESSION['msg'] = ['texto' => $texto, 'tipo' => $tipo];
}

/* log da batalha (guarda as ultimas 30) */
function jlog($txt) {
  $_SESSION['log'][] = $txt;
  if (count($_SESSION['log']) > 30) {
    $_SESSION['log'] = array_slice($_SESSION['log'], -30);
  }
}

/* efeitos visuais de uma rodada, consumidos quando a pagina remonta */
function fx($k, $v = null) {
  if ($v === null) {
    if (!isset($_SESSION['fx'][$k])) return null;
    return $_SESSION['fx'][$k];
  }
  $_SESSION['fx'][$k] = $v;
}
function fxNum($lado, $txt, $cor) {
  $_SESSION['fx']['num'][$lado][] = ['txt' => $txt, 'cor' => $cor];
}
function fxHit($lado, $dano) {
  $_SESSION['fx']['hit'][$lado] = $dano;
  $_SESSION['fx']['arena'] = true;
}
function consomeFx() {
  $f = $_SESSION['fx'];
  $_SESSION['fx'] = [];
  return $f;
}

/* ---------- monstros ---------- */
function criarLado($m, $nivel) {
  $esc = 1 + 0.05 * ($nivel - 5);
  return [
    'nome' => $m['nome'], 'img' => $m['img'], 'tipo' => $m['tipo'], 'nivel' => $nivel,
    'hpMax' => (int)round($m['hp'] * $esc), 'hp' => (int)round($m['hp'] * $esc),
    'atk' => $m['atk'] + ($nivel - 5), 'def' => $m['def'] + (int)floor(($nivel - 5) / 2),
    'golpes' => [
      ['n' => $m['golpe'], 'p' => $m['poder'], 't' => $m['tipo']],
      ['n' => 'Investida', 'p' => 14, 't' => 'neutro']
    ],
    'pocao' => 2, 'defendendo' => false, 'forca' => 0, 'escudo' => 0, 'status' => null
  ];
}

/* sorteia um selvagem (os iniciais nao entram, menos Fadalux e Lutalux) */
function sortearInimigo() {
  global $MONSTROS;
  $m = $MONSTROS[mt_rand(0, count($MONSTROS) - 1)];
  while (!empty($m['inicial']) && empty($m['selvagem'])) {
    $m = $MONSTROS[mt_rand(0, count($MONSTROS) - 1)];
  }
  return $m;
}

/* novo desafio: nivel do selvagem sobe com as vitorias seguidas (ate 12) */
function proximoDesafio() {
  $nv = min(5 + $_SESSION['vitorias'], 12);
  $inimigo = criarLado(sortearInimigo(), $nv);
  $inimigo['pocao'] = 1;   // o selvagem so leva 1 pocao pra luta
  $_SESSION['inimigo'] = $inimigo;
  $_SESSION['resultado'] = null;
  $_SESSION['jogador']['pocao'] = $_SESSION['estoque']['pocoes'];   // poções compradas na loja
  $_SESSION['jogador']['forca'] = 0;
  $_SESSION['jogador']['escudo'] = 0;
  $_SESSION['jogador']['status'] = null;   // status da luta anterior somem
  $_SESSION['log'] = [];
  jlog('Um ' . $inimigo['nome'] . ' selvagem apareceu no bioma ' . $GLOBALS['NOME_BIOMA'][$inimigo['tipo']] . '!');
  fx('novo', true);
}

/* ---------- calculo de dano (mesmas formulas da versao JavaScript) ---------- */
function calcDano($a, $d, $golpe, $defendendo) {
  global $EFICAZ, $FRACO;
  $mult = 1;
  $extra = '';
  if (in_array($d['tipo'], $EFICAZ[$golpe['t']])) {
    $mult = 1.5;
    $extra = ' É super efetivo!';
  } elseif (in_array($d['tipo'], $FRACO[$golpe['t']])) {
    $mult = 0.6;
    $extra = ' Não é muito efetivo...';
  }
  if (mt_rand() / mt_getrandmax() < 0.12) {
    $mult *= 1.4;
    $extra .= ' Acerto crítico!';
  }
  $queima = ($a['status'] && $a['status']['s'] === 'queimadura') ? 0.75 : 1;
  $dano = ($a['atk'] * pow(1.25, $a['forca']) * $queima + $golpe['p']) - $d['def'] * pow(1.3, $d['escudo']);
  if ($defendendo) $mult *= 0.4;
  $dano = $dano * $mult * (0.9 + (mt_rand() / mt_getrandmax()) * 0.2);
  if ($dano < 1) $dano = 1;
  return ['dano' => (int)round($dano), 'extra' => $extra];
}

/* ---------- condicoes de status ---------- */
function aplicarStatus(&$alvo, $lado, $tipo) {
  global $MSG_STATUS, $TAGS_STATUS, $CORES_STATUS;
  if ($alvo['status'] || $alvo['hp'] <= 0) return;
  $turnos = ($tipo === 'sono') ? mt_rand(1, 3) : 0;
  $alvo['status'] = ['s' => $tipo, 'turnos' => $turnos];
  fxNum($lado, $TAGS_STATUS[$tipo], $CORES_STATUS[$tipo]);
  jlog($alvo['nome'] . $MSG_STATUS[$tipo]);
}

function aplicarEfeitoGolpe($golpe, &$alvo, $lado) {
  global $STATUS_DO_TIPO;
  if (isset($STATUS_DO_TIPO[$golpe['t']]) && !$alvo['status'] && $alvo['hp'] > 0
      && (mt_rand() / mt_getrandmax()) < 0.15) {
    aplicarStatus($alvo, $lado, $STATUS_DO_TIPO[$golpe['t']]);
  }
}

/* sono, gelo e paralisia podem fazer perder a vez; devolve true se conseguiu agir */
function tentarAgir(&$quem) {
  if (!$quem['status']) return true;
  $s = $quem['status']['s'];
  if ($s === 'sono') {
    if ($quem['status']['turnos'] > 0) {
      $quem['status']['turnos']--;
      jlog($quem['nome'] . ' está dormindo e não conseguiu se mexer!');
      return false;
    }
    $quem['status'] = null;
    jlog($quem['nome'] . ' acordou!');
    return true;
  }
  if ($s === 'congelado') {
    if (mt_rand() / mt_getrandmax() < 0.2) {
      $quem['status'] = null;
      jlog($quem['nome'] . ' descongelou!');
      return true;
    }
    jlog($quem['nome'] . ' está congelado e não conseguiu se mexer!');
    return false;
  }
  if ($s === 'paralisia') {
    if (mt_rand() / mt_getrandmax() < 0.25) {
      jlog($quem['nome'] . ' está paralisado e perdeu o turno!');
      return false;
    }
    return true;
  }
  return true;
}

/* no fim da rodada veneno e queimadura tiram HP de quem estiver sofrendo */
function danoStatusRodada() {
  global $CORES_STATUS;
  $jogador = &$_SESSION['jogador'];
  $inimigo = &$_SESSION['inimigo'];
  $morto = null;
  if ($jogador['status'] && in_array($jogador['status']['s'], ['veneno', 'queimadura'])) {
    $d = max(1, (int)round($jogador['hpMax'] / 16));
    $jogador['hp'] -= $d;
    fxNum('j', '-' . $d, $CORES_STATUS[$jogador['status']['s']]);
    jlog($jogador['nome'] . ' perdeu ' . $d . ' de HP por ' . ($jogador['status']['s'] === 'veneno' ? 'veneno' : 'queimadura') . '!');
    if ($jogador['hp'] <= 0) $morto = 'jogador';
  }
  if ($morto === null && $inimigo['status'] && in_array($inimigo['status']['s'], ['veneno', 'queimadura'])) {
    $d = max(1, (int)round($inimigo['hpMax'] / 16));
    $inimigo['hp'] -= $d;
    fxNum('i', '-' . $d, $CORES_STATUS[$inimigo['status']['s']]);
    jlog($inimigo['nome'] . ' perdeu ' . $d . ' de HP por ' . ($inimigo['status']['s'] === 'veneno' ? 'veneno' : 'queimadura') . '!');
    if ($inimigo['hp'] <= 0) $morto = 'inimigo';
  }
  if ($morto !== null) {
    fim($morto === 'inimigo');
    return;
  }
  jlog('O que ' . $jogador['nome'] . ' vai fazer?');
}

/* ---------- golpes ---------- */
function golpeDoJogador($indice) {
  $jogador = &$_SESSION['jogador'];
  $inimigo = &$_SESSION['inimigo'];
  $golpe = $jogador['golpes'][$indice];
  fx('proj', ['dir' => 'd', 'tipo' => $golpe['t']]);
  $res = calcDano($jogador, $inimigo, $golpe, $inimigo['defendendo']);
  jlog($jogador['nome'] . ' usou ' . $golpe['n'] . '!' . $res['extra']);
  $inimigo['hp'] -= $res['dano'];
  $inimigo['defendendo'] = false;
  fxHit('i', $res['dano']);
  if ($inimigo['hp'] <= 0) {
    fim(true);
    return;
  }
  aplicarEfeitoGolpe($golpe, $inimigo, 'i');
}

function golpeDoInimigo() {
  $jogador = &$_SESSION['jogador'];
  $inimigo = &$_SESSION['inimigo'];
  $golpe = (mt_rand() / mt_getrandmax() < 0.7) ? $inimigo['golpes'][0] : $inimigo['golpes'][1];
  fx('proj', ['dir' => 'e', 'tipo' => $golpe['t']]);
  $res = calcDano($inimigo, $jogador, $golpe, $jogador['defendendo']);
  jlog($inimigo['nome'] . ' usou ' . $golpe['n'] . '!' . $res['extra']);
  $jogador['hp'] -= $res['dano'];
  $jogador['defendendo'] = false;
  fxHit('j', $res['dano']);
  if ($jogador['hp'] <= 0) {
    fim(false);
    return;
  }
  aplicarEfeitoGolpe($golpe, $jogador, 'j');
}

/* a vez do selvagem: pode se curar ou atacar */
function turnoInimigo() {
  $inimigo = &$_SESSION['inimigo'];
  $jogador = &$_SESSION['jogador'];
  if ($inimigo['hp'] <= 0 || $jogador['hp'] <= 0) return;
  if (!tentarAgir($inimigo)) {
    danoStatusRodada();
    return;
  }
  if ($inimigo['pocao'] > 0 && $inimigo['hp'] <= $inimigo['hpMax'] * 0.3 && (mt_rand() / mt_getrandmax()) < 0.55) {
    $inimigo['pocao']--;
    $antes = $inimigo['hp'];
    $inimigo['hp'] = min($inimigo['hpMax'], $inimigo['hp'] + (int)round($inimigo['hpMax'] * 0.35));
    fxNum('i', '+' . ($inimigo['hp'] - $antes), '#5dff8a');
    jlog('Glub! ' . $inimigo['nome'] . ' recuperou ' . ($inimigo['hp'] - $antes) . ' de HP!');
  } else {
    golpeDoInimigo();
  }
  danoStatusRodada();
}

/* ---------- fim de batalha ---------- */
function fim($venceu) {
  $jogador = &$_SESSION['jogador'];
  $inimigo = &$_SESSION['inimigo'];
  if ($venceu) {
    $_SESSION['vitorias']++;
    if ($_SESSION['vitorias'] > $_SESSION['recorde']) $_SESSION['recorde'] = $_SESSION['vitorias'];
    $jogador['nivel']++;
    $jogador['atk']++;
    $jogador['def']++;
    $jogador['hpMax'] += 5;
    /* premio: 10 moedas por vitória, 15 quando ja tem 3 ou mais seguidas */
    $ganho = ($_SESSION['vitorias'] >= 3) ? 15 : 10;
    $_SESSION['moedas'] += $ganho;
    $antes = $jogador['hp'];
    $jogador['hp'] = min($jogador['hpMax'], $jogador['hp'] + (int)round($jogador['hpMax'] * 0.35));
    $cura = $jogador['hp'] - $antes;
    $_SESSION['resultado'] = [
      'titulo' => 'Vitória!',
      'texto' => $inimigo['nome'] . ' foi derrotado! ' . $jogador['nome'] . ' subiu pro Nv. ' . $jogador['nivel']
        . ' e recuperou ' . $cura . ' de HP. Você ganhou ' . $ganho . ' moedas!',
      'placar' => 'Vitórias seguidas: ' . $_SESSION['vitorias'] . ' · Recorde: ' . $_SESSION['recorde'],
      'tipo' => 'vitoria'
    ];
  } else {
    $_SESSION['vitorias'] = 0;
    $jogador['hp'] = $jogador['hpMax'];   // o Centro de Cura revive o parceiro
    $_SESSION['resultado'] = [
      'titulo' => 'Derrota...',
      'texto' => $jogador['nome'] . ' desmaiou, mas o Centro de Cura deixou ele novo em folha pra próxima!',
      'placar' => 'Vitórias seguidas: 0 · Recorde: ' . $_SESSION['recorde'],
      'tipo' => 'derrota'
    ];
  }
}

/* ---------- usar itens na batalha (as regras da mochila) ---------- */
function usarItemNaBatalha($chave) {
  $item = buscarItem($chave);
  if (!$item) return;
  $jogador = &$_SESSION['jogador'];
  $estoque = &$_SESSION['estoque'];

  if ($chave === 'reviver') {
    msg('O Reviver é usado sozinho: quando o parceiro desmaia, aparece o botão pra usá-lo.');
    return;
  }
  if ($chave === 'forca' || $chave === 'escudo') {
    $usos = ($chave === 'forca') ? $jogador['forca'] : $jogador['escudo'];
    if ($estoque[$chave] <= 0 || $usos >= 3) {
      msg('Sem estoque, ou já usou 3 vezes nesta batalha.', 'ruim');
      return;
    }
    $estoque[$chave]--;
    if ($chave === 'forca') {
      $jogador['forca']++;
      fxNum('j', 'ATK+', '#ffd25d');
      jlog($jogador['nome'] . ' usou a Poção de Força! Ataque aumentou (uso ' . $jogador['forca'] . ' de 3).');
    } else {
      $jogador['escudo']++;
      fxNum('j', 'DEF+', '#6dc7ff');
      jlog($jogador['nome'] . ' usou a Poção de Escudo! Defesa aumentou (uso ' . $jogador['escudo'] . ' de 3).');
    }
    turnoInimigo();
    return;
  }
  if ($chave === 'pocoes') {
    if ($jogador['pocao'] <= 0) { msg('Você não tem Poção de Cura.', 'ruim'); return; }
    if ($jogador['hp'] >= $jogador['hpMax']) { msg('A vida já está cheia!', 'ruim'); return; }
    $jogador['pocao']--;
    $estoque['pocoes']--;
    $antes = $jogador['hp'];
    $jogador['hp'] = min($jogador['hpMax'], $jogador['hp'] + (int)round($jogador['hpMax'] * 0.35));
    fxNum('j', '+' . ($jogador['hp'] - $antes), '#5dff8a');
    jlog('Glub! ' . $jogador['nome'] . ' recuperou ' . ($jogador['hp'] - $antes) . ' de HP!');
    turnoInimigo();
    return;
  }
  if ($estoque[$chave] <= 0) { msg('Você não tem esse item.', 'ruim'); return; }

  /* restauradores de HP */
  if (isset($item['cura'])) {
    $faltandoStatus = !empty($jogador['status']);
    if ($jogador['hp'] >= $jogador['hpMax'] && !(isset($item['statusTodos']) && $faltandoStatus)) {
      msg('A vida já está cheia!', 'ruim');
      return;
    }
    $estoque[$chave]--;
    if ($item['cura'] === 'total') {
      $ganho = $jogador['hpMax'] - $jogador['hp'];
      $jogador['hp'] = $jogador['hpMax'];
    } else {
      $ganho = min($item['hpfixo'], $jogador['hpMax'] - $jogador['hp']);
      $jogador['hp'] += $ganho;
    }
    global $NOMES_STATUS;
    $txt = $jogador['nome'] . ' recuperou ' . $ganho . ' de HP!';
    if (isset($item['statusTodos']) && $jogador['status']) {
      $txt .= ' E não está mais ' . $NOMES_STATUS[$jogador['status']['s']] . '!';
      $jogador['status'] = null;
    }
    fxNum('j', '+' . $ganho, '#5dff8a');
    jlog($txt);
    turnoInimigo();
    return;
  }

  /* curas de condicao */
  if (isset($item['curaStatus'])) {
    global $NOMES_STATUS;
    if (!$jogador['status'] || ($item['curaStatus'] !== 'todos' && $jogador['status']['s'] !== $item['curaStatus'])) {
      msg($jogador['nome'] . ' não está com esse problema.', 'ruim');
      return;
    }
    $era = $jogador['status']['s'];
    $estoque[$chave]--;
    $jogador['status'] = null;
    fxNum('j', 'CURADO', '#5dff8a');
    jlog($item['curaStatus'] === 'todos'
      ? $jogador['nome'] . ' foi curado de todos os problemas de status!'
      : $jogador['nome'] . ' não está mais ' . $NOMES_STATUS[$era] . '!');
    turnoInimigo();
    return;
  }
}

/* ---------- acoes do POST (cada clique é um turno) ---------- */
function processarAcao($acao) {
  global $ITENS;
  sessao();
  $jogador = &$_SESSION['jogador'];

  if ($acao === 'fugir') {
    $_SESSION['vitorias'] = 0;
    $_SESSION['inimigo'] = null;
    $_SESSION['resultado'] = null;
    header('Location: index.php');
    exit;
  }
  if ($acao === 'reviver') {
    if ($_SESSION['resultado'] && $_SESSION['resultado']['tipo'] === 'derrota' && $_SESSION['estoque']['reviver'] > 0) {
      $_SESSION['estoque']['reviver']--;
      $jogador['hp'] = max(1, (int)round($jogador['hpMax'] / 2));
      $jogador['status'] = null;
      $_SESSION['resultado'] = null;
      fxNum('j', 'REVIVE', '#ffd25d');
      jlog($jogador['nome'] . ' voltou com metade do HP graças ao Reviver!');
      jlog('O que ' . $jogador['nome'] . ' vai fazer?');
    }
    return;
  }
  if ($acao === 'novo') {
    proximoDesafio();
    return;
  }
  if ($_SESSION['resultado']) return;   // batalha acabou, so sobram as acoes do resultado
  if ($jogador['hp'] <= 0) return;

  if ($acao === 'golpe0' || $acao === 'golpe1') {
    if (!tentarAgir($jogador)) {
      turnoInimigo();
      return;
    }
    golpeDoJogador($acao === 'golpe0' ? 0 : 1);
    if (!$_SESSION['resultado']) turnoInimigo();
    return;
  }
  if ($acao === 'defender') {
    $jogador['defendendo'] = true;
    jlog($jogador['nome'] . ' se preparou pra defender!');
    turnoInimigo();
    return;
  }
  if ($acao === 'item') {
    $chave = isset($_POST['item']) ? $_POST['item'] : '';
    usarItemNaBatalha($chave);
    return;
  }
}

/* ---------- helpers pra montar a pagina ---------- */
function e($txt) {
  return htmlspecialchars((string)$txt, ENT_QUOTES, 'UTF-8');
}

function barraClasse($pct) {
  if ($pct <= 25) return 'barra-vida vermelha';
  if ($pct <= 55) return 'barra-vida amarela';
  return 'barra-vida';
}

function chipStatus($lado) {
  global $TAGS_STATUS, $CORES_STATUS;
  if (empty($lado['status'])) return '';
  return ' <span class="chip-status" style="background:' . $CORES_STATUS[$lado['status']['s']] . '">'
    . $TAGS_STATUS[$lado['status']['s']] . '</span>';
}

function caixaStatus($lado, $mostrarNumeros) {
  $pct = max(0, (int)round($lado['hp'] / $lado['hpMax'] * 100));
  $h = '<div class="nome-nv"><span>' . e($lado['nome']) . chipStatus($lado) . '</span><span>Nv. ' . $lado['nivel'] . '</span></div>';
  $h .= '<div class="barra-externa"><div class="' . barraClasse($pct) . '" style="width:' . $pct . '%"></div></div>';
  if ($mostrarNumeros) {
    $h .= '<div class="hp-numeros">' . max(0, $lado['hp']) . ' / ' . $lado['hpMax'] . '</div>';
  }
  return $h;
}

/* sprite do monstro: sombra no chao + imagem colada no jogo */
function spriteHtml($lado) {
  global $FIGURAS;
  return '<div class="sombra-chao"></div><div class="corpo"><img class="sprite-img" alt="' . e($lado['nome'])
    . '" src="' . $FIGURAS[$lado['img']] . '"></div>';
}

/* numeros flutuantes de um lado (dano, cura, status...) */
function numsHtml($fx, $lado) {
  if (empty($fx['num'][$lado])) return '';
  $h = '';
  $i = 0;
  foreach ($fx['num'][$lado] as $n) {
    $esq = 35 + ($i % 3) * 15;   // espalha pra nao sobrepor os numeros
    $h .= '<span class="num-flutuante" style="color:' . $n['cor'] . '; left:' . $esq . '%">' . e($n['txt']) . '</span>';
    $i++;
  }
  return $h;
}

function botaoItem($item, $qtd, $extraDisabled = false) {
  global $CORES_STATUS;
  $bloq = ($qtd <= 0 || $extraDisabled) ? ' disabled' : '';
  return '<form method="post"><input type="hidden" name="item" value="' . e($item['chave']) . '">'
    . '<button class="btn-usar" name="acao" value="item"' . $bloq . '>Usar</button></form>';
}