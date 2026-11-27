<?php
/* ============================================================
   TELA DE ESCOLHA DO PARCEIRO (PHP)
   mostra os 5 parceiros iniciais, a faixa com a vida do parceiro
   atual, o Centro de Cura e o atalho pra loja.
   ============================================================ */

require_once __DIR__ . '/funcoes.php';
sessao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['acao']) && $_POST['acao'] === 'escolher' && isset($_POST['i'])) {
    $i = (int)$_POST['i'];
    if (isset($MONSTROS[$i]) && !empty($MONSTROS[$i]['inicial'])) {
      $m = $MONSTROS[$i];
      /* se for o mesmo parceiro, mantém o progresso; parceiro novo comeca do zero */
      if (empty($_SESSION['jogador']) || $_SESSION['jogador']['nome'] !== $m['nome']) {
        $_SESSION['jogador'] = criarLado($m, 5);
        $_SESSION['vitorias'] = 0;
      }
      proximoDesafio();
      header('Location: batalha.php');
      exit;
    }
  }
  if (isset($_POST['acao']) && $_POST['acao'] === 'cura' && !empty($_SESSION['jogador'])) {
    $_SESSION['jogador']['hp'] = $_SESSION['jogador']['hpMax'];
    $_SESSION['jogador']['status'] = null;
    msg($_SESSION['jogador']['nome'] . ' saiu do Centro de Cura novo em folha! Poções novas agora só na Loja.', 'boa');
  }
  header('Location: index.php');
  exit;
}

$jogador = empty($_SESSION['jogador']) ? null : $_SESSION['jogador'];
$msg = $_SESSION['msg'];
$_SESSION['msg'] = null;
$temBatalha = $jogador && !empty($_SESSION['inimigo']) && empty($_SESSION['resultado']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monstros</title>
<style>
body {
  background: linear-gradient(rgba(10,14,26,.55), rgba(10,14,26,.55)), url("<?php echo $IMG_ESCOLHA; ?>");
  background-size: cover;
  background-position: center;
}
</style>
<link rel="stylesheet" href="estilo-batalha.css">
</head>
<body>

<div id="tela-escolha">
  <h1>Monstros</h1>
  <p id="sub-escolha">Escolha 1 dos 5 parceiros pra encarar monstros aleatórios!</p>
  <p id="linha-moedas"><span id="num-moedas"><?php echo $_SESSION['moedas']; ?></span> moedas &nbsp;·&nbsp; <span id="num-pocoes"><?php echo $_SESSION['estoque']['pocoes']; ?></span> poções de cura</p>

  <?php if ($msg): ?>
    <div id="aviso-jogo" class="<?php echo e($msg['tipo']); ?>"><?php echo e($msg['texto']); ?></div>
  <?php endif; ?>

  <?php if ($jogador): ?>
    <div id="faixa-parceiro">
      <span id="fp-nome"><?php echo e($jogador['nome']); ?> · Nv. <?php echo $jogador['nivel']; ?></span>
      <div class="barra-externa">
        <?php
        $pct = max(0, (int)round($jogador['hp'] / $jogador['hpMax'] * 100));
        echo '<div class="' . barraClasse($pct) . '" style="width:' . $pct . '%"></div>';
        ?>
      </div>
      <span id="fp-hp"><?php echo max(0, $jogador['hp']); ?> / <?php echo $jogador['hpMax']; ?></span>
    </div>
  <?php endif; ?>

  <div id="grade-iniciais">
    <?php foreach ($MONSTROS as $i => $m): if (empty($m['inicial'])) continue; ?>
      <form method="post">
        <input type="hidden" name="i" value="<?php echo $i; ?>">
        <button class="cartao" name="acao" value="escolher" style="border-top:8px solid <?php echo $COR_TIPO[$m['tipo']]; ?>">
          <div class="emo"><img class="sprite-img" alt="<?php echo e($m['nome']); ?>" src="<?php echo $FIGURAS[$m['img']]; ?>"></div>
          <div class="nm"><?php echo e($m['nome']); ?></div>
          <span class="badge" style="background:<?php echo $COR_TIPO[$m['tipo']]; ?>"><?php echo $NOME_TIPO[$m['tipo']]; ?></span>
          <div class="st">HP <?php echo $m['hp']; ?> · Ataque <?php echo $m['atk']; ?> · Defesa <?php echo $m['def']; ?></div>
          <?php if (!empty($m['frase'])): ?><div class="frase"><?php echo e($m['frase']); ?></div><?php endif; ?>
        </button>
      </form>
    <?php endforeach; ?>
  </div>

  <div class="atalhos-escolha">
    <form method="post"><button id="btn-centro" name="acao" value="cura"<?php echo $jogador ? '' : ' disabled'; ?>><span class="cruz"></span><span>Centro<br>de Cura</span></button></form>
    <a id="btn-loja" href="loja.php"><span>Loja<br>de Itens</span></a>
    <?php if ($temBatalha): ?><a id="btn-voltar-batalha" href="batalha.php">Voltar pra batalha</a><?php endif; ?>
  </div>

  <p id="dica-escolha">Cada tipo é forte contra uns e fraco contra outros. Boa sorte!</p>
</div>

</body>
</html>
