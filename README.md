# Monstros — jogo em PHP

Versão em PHP puro do jogo (sem JavaScript nenhum). Tudo roda no servidor:
cada ação é um formulário POST, o PHP processa o turno e remonta a página,
e o estado (moedas, mochila, batalha) fica na sessão ($_SESSION).

## Como rodar

Com o PHP instalado (XAMPP, ou `php-cli`), dentro desta pasta:

    php -S localhost:8080

e abrir http://localhost:8080/index.php no navegador.

No XAMPP: copie a pasta `monstros-php` pra dentro de `htdocs` e abra
http://localhost/monstros-php/index.php

## Arquivos

| Arquivo              | O que faz |
|----------------------|-----------|
| index.php            | Escolha do parceiro (5 iniciais) + Centro de Cura |
| batalha.php          | Batalha por turnos: golpes, Defender, itens, status, Reviver |
| loja.php             | Loja completa: Restauradores de HP, Cura de Condição, Reviver e poções da casa |
| dados.php            | Monstros, tipos (eficácia/fraco), itens e condições de status |
| funcoes.php          | Regras do jogo: dano, status, IA do inimigo, vitória/derrota |
| dados_sprites.php    | Sprites dos monstros e biomas (gerado, em base64) |
| estilo-batalha.css   | CSS do jogo (escolha + batalha) |
| estilo-loja.css      | CSS da loja |

## O que tem no jogo

- 20 monstros de 12 tipos (incluindo Fadalux, tipo Fada, e Lutalux, tipo Lutador)
- Tipos com tabela de eficácia, cores, biomas e projéteis próprios
- Condições de status: veneno, queimadura, congelamento, sono e paralisia
  (veneno e queimadura tiram HP por rodada; sono/gelo/paralisia roubam turnos)
- 15 itens na loja: Potion 20 HP, Super Potion 50, Hyper Potion 200,
  Max Potion 100%, Full Restore (100% + status), Antidote, Burn Heal,
  Ice Heal, Awakening, Paralyze Heal, Full Heal, Reviver (revive com
  metade do HP) e as 3 poções da casa
- Moedas por vitória (10; 15 com 3+ vitórias seguidas), histórico de compras
  e Centro de Cura de graça
