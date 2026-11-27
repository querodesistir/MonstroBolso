<?php
/* ============================================================
   LOJA DE ITENS (PHP puro)
   moedas e estoques moram na sessao. a compra tem dois passos:
   clicar no item mostra o cartao de confirmacao, e só o botao
   Confirmar tira as moedas e guarda o item na mochila.
   ============================================================ */

require_once __DIR__ . '/funcoes.php';
sessao();

$confirmar = null;   // item aguardando confirmacao

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
  $acao = $_POST['acao'];
  $chave = isset($_POST['item']) ? $_POST['item'] : '';
  $item = buscarItem($chave);

  if ($acao === 'ver' && $item) {
    $_SESSION['confirmar'] = $chave;
  } elseif ($acao === 'cancelar') {
    $_SESSION['confirmar'] = null;
    msg('Compra cancelada. Suas moedas estão seguras!');
  } elseif ($acao === 'confirmar' && $item) {
    if ($_SESSION['moedas'] < $item['preco']) {
      msg('Moedas insuficientes! O ' . $item['nome'] . ' custa ' . $item['preco'] . ' e você tem ' . $_SESSION['moedas'] . '. Vença batalhas pra ganhar mais!', 'ruim');
    } else {
      $_SESSION['moedas'] -= $item['preco'];
      $_SESSION['estoque'][$chave]++;
      $lista = $_SESSION['historico'];
      array_unshift($lista, ['nome' => $item['nome'], 'preco' => $item['preco'], 'quando' => date('G:i')]);
      $_SESSION['historico'] = array_slice($lista, 0, 5);
      msg($item['nome'] . ' guardado na mochila! Use na batalha, na área Mochila.', 'boa');
    }
    $_SESSION['confirmar'] = null;
  }
  header('Location: loja.php');
  exit;
}

$confirmarChave = isset($_SESSION['confirmar']) ? $_SESSION['confirmar'] : null;
$_SESSION['confirmar'] = null;
if ($confirmarChave) $confirmar = buscarItem($confirmarChave);
$msg = $_SESSION['msg'];
$_SESSION['msg'] = null;

$temBatalha = !empty($_SESSION['jogador']) && !empty($_SESSION['inimigo']) && empty($_SESSION['resultado']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loja de Itens — Monstros</title>
<style>
body {
  background: linear-gradient(rgba(10,16,30,.35), rgba(10,16,30,.55)), url("<?php echo $IMG_FLORESTA; ?>");
  background-size: cover;
  background-position: center;
}
</style>
<link rel="stylesheet" href="estilo-loja.css">
</head>
<body>

<!-- a janela principal da loja, estilo Poke Mart -->
<div class="mart-janela">
  <div class="mart-topo">
    <div class="mart-titulo">Loja de Itens
      <span>Monstros · centro de itens</span>
    </div>
    <div class="mart-topo-dir">
      <div id="caixa-moedas">
        <span id="num-moedas"><?php echo $_SESSION['moedas']; ?></span>
      </div>
      <a class="btn-fechar" href="<?php echo $temBatalha ? 'batalha.php' : 'index.php'; ?>">Fechar</a>
    </div>
  </div>

  <div class="mart-sub">
    Bem-vindo! Gaste as <b>moedas</b> que você ganha vencendo batalhas (cada vitória dá <b>10</b>, e com
    3 vitórias seguidas cada uma vale <b>15</b>). Clica num item pra conferir antes de comprar!
  </div>

  <?php if ($msg): ?>
    <div id="aviso-jogo" class="<?php echo e($msg['tipo']); ?>"><?php echo e($msg['texto']); ?></div>
  <?php endif; ?>

  <?php if ($confirmar): ?>
    <div id="cartao-confirmar">
      <span>Comprar 1 <?php echo e($confirmar['nome']); ?> por <?php echo $confirmar['preco']; ?> moedas?</span>
      <span class="botoes-confirmar">
        <form method="post">
          <input type="hidden" name="item" value="<?php echo e($confirmar['chave']); ?>">
          <button class="btn-sim" name="acao" value="confirmar">Confirmar</button>
        </form>
        <form method="post"><button class="btn-nao" name="acao" value="cancelar">Cancelar</button></form>
      </span>
    </div>
  <?php endif; ?>

  <!-- os itens à venda, separados por seção -->
  <div class="mart-lista" id="mart-lista">
    <?php $par = 0; foreach ($GRUPOS_ITENS as $grupo): ?>
      <div class="mart-secao"><?php echo e($grupo['titulo']); ?></div>
      <?php foreach ($ITENS as $item): if ($item['grupo'] !== $grupo['id']) continue; $par++; ?>
        <form method="post">
          <input type="hidden" name="item" value="<?php echo e($item['chave']); ?>">
          <button class="mart-item<?php echo $par % 2 ? '' : ' impar'; ?>" name="acao" value="ver"<?php echo $_SESSION['moedas'] < $item['preco'] ? ' disabled' : ''; ?>>
            <span class="mi-nome"><?php echo e($item['nome']); ?>
              <span class="mi-tag"><?php echo e($item['desc']); ?></span></span>
            <span class="mi-preco"><?php echo $item['preco']; ?> moedas</span>
          </button>
        </form>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </div>

  <div class="mart-pes">
    Restauradores recuperam HP durante a luta · Curas de condição resolvem veneno, queimadura,
    congelamento, sono e paralisia · Reviver traz o parceiro de volta com metade do HP ·
    tudo aparece na área Mochila da batalha
  </div>
</div>

<!-- mochila: tudo que eu já comprei -->
<div class="mart-janela" id="mochila">
  <div class="painel-titulo">Minha mochila</div>
  <div class="painel-corpo">
    <p class="nota">Tudo que você comprou fica guardado até o fim da sessão.</p>
    <?php foreach ($ITENS as $item): ?>
      <div class="linha">
        <span><?php echo e($item['nome']); ?></span>
        <span><span class="usavel">usável na batalha</span> <span class="qtd">x<?php echo $_SESSION['estoque'][$item['chave']]; ?></span></span>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- como funciona, passo a passo -->
<div class="mart-janela" id="como-funciona">
  <div class="painel-titulo">Como funciona</div>
  <div class="painel-corpo">
    <div class="passo">
      <span class="numero">1</span>
      <span><b>Lute na batalha:</b> cada monstro selvagem que você derrota dá <b>10 moedas</b> na hora.</span>
    </div>
    <div class="passo">
      <span class="numero">2</span>
      <span><b>Ganhe bônus:</b> com 3 vitórias seguidas (ou mais) cada vitória vale <b>15 moedas</b> em vez de 10.</span>
    </div>
    <div class="passo">
      <span class="numero">3</span>
      <span><b>Compre aqui:</b> moedas viram itens. <b>Restauradores de HP</b> curam a vida (Potion 20, Super Potion 50, Hyper Potion 200, Max Potion e Full Restore 100%). <b>Curas de condição</b> resolvem os status. O <b>Reviver</b> traz o parceiro desmaiado de volta com metade do HP.</span>
    </div>
    <div class="passo">
      <span class="numero">4</span>
      <span><b>Use na batalha:</b> na tela de luta, a área <b>Mochila de itens</b> lista tudo que você tem, com o efeito e a quantidade de cada item.</span>
    </div>
    <div class="passo">
      <span class="numero">5</span>
      <span><b>Cuidado com os status:</b> golpes de fogo podem queimar, de gelo congelar, de elétrico paralisar, de planta e fada fazer dormir e de sombra envenenar. Veneno e queimadura tiram HP a cada rodada; sono, congelamento e paralisia roubam turnos.</span>
    </div>
    <div class="passo">
      <span class="numero">6</span>
      <span><b>Tudo no servidor:</b> esta loja é feita em PHP — a compra passa por formulário POST, o estado fica na <b>sessão</b> ($_SESSION) e a página inteira é remontada pelo servidor. Nada de JavaScript.</span>
    </div>
  </div>
</div>

<!-- histórico das compras -->
<div class="mart-janela" id="historico">
  <div class="painel-titulo">Últimas compras</div>
  <div class="painel-corpo">
    <p class="nota">As 5 compras mais recentes ficam anotadas aqui.</p>
    <ul id="lista-historico">
      <?php if (empty($_SESSION['historico'])): ?>
        <li class="vazio">Você ainda não comprou nada por aqui...</li>
      <?php else: foreach ($_SESSION['historico'] as $compra): ?>
        <li>
          <span><?php echo e($compra['nome']); ?> (-<?php echo $compra['preco']; ?> moedas)</span>
          <span class="quando">às <?php echo e($compra['quando']); ?></span>
        </li>
      <?php endforeach; endif; ?>
    </ul>
  </div>
</div>

<!-- rodapé com a dica -->
<div id="rodape">
  <a class="btn-voltar" href="<?php echo $temBatalha ? 'batalha.php' : 'index.php'; ?>">
    <?php echo $temBatalha ? 'Voltar pra batalha' : 'Voltar pra escolha'; ?>
  </a>
  <p id="dica-ganho">
    Dica: sem moedas? Vá na batalha e vença monstros selvagens pra ganhar mais!<br>
    O Centro de Cura continua de graça, mas itens agora só na loja mesmo.
  </p>
</div>

</body>
</html>
