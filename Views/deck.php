
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meu Deck - MonstroBolso</title>

    <link rel="stylesheet" href="css/deck.css">
    <style>

/* CONFIGURAÇÕES GERAIS */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: 'Trebuchet MS', Arial, sans-serif;
    background-color: #1c2333;
    color: #f8f0d8;
    min-height: 100vh;
}


/* CABEÇALHO */
.cabecalho {
    text-align: center;
    padding: 35px 15px;

    background: linear-gradient(
        135deg,
        #354568,
        #1c2333
    );
    border-bottom: 4px solid #ffd75e;
}
.cabecalho h1 {
    color: #ffd75e;
    font-size: 36px;
    letter-spacing: 3px;
    text-shadow: 3px 3px #111;
}
.cabecalho p {
    margin-top: 10px;
    color: #cfd8ee;
}


/* CONTEÚDO PRINCIPAL */
.conteudo {
    max-width: 1000px;
    margin: 35px auto;
    padding: 0 20px;
}


/* SEÇÕES */
.secao {
    background: #2d3a5c;
    padding: 22px;
    margin-bottom: 30px;
    border-radius: 15px;
    border: 2px solid #52658e;
    box-shadow: 5px 5px 0 #111827;
}

/* TÍTULOS DAS SEÇÕES */
.titulo-secao {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.titulo-secao h2 {
    color: #ffd75e;
    font-size: 24px;
}
.titulo-secao p {
    color: #cfd8ee;
    font-size: 13px;
    margin-top: 5px;
}


/* CONTADOR */
.contador {
    background: #ffd75e;
    color: #252525;
    padding: 9px 15px;
    border-radius: 20px;
    font-weight: bold;
}


/* TABELAS */
.tabela-container {
    width: 100%;
    overflow-x: auto;
    border-radius: 10px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: #f8f0d8;
    color: #252525;
    min-width: 600px;
}
th {
    background: #354568;
    color: #ffffff;
    padding: 14px 10px;
    text-align: center;
    font-size: 14px;
}
td {
    padding: 13px 10px;
    text-align: center;
    border-bottom: 1px solid #d1c7a9;
}


/* NOME DOS MONSTROS */
.nome-monstro {
    font-weight: bold;
    color: #354568;
}


/* FOTOS DOS MONSTROS */
.foto-monstro {
    width: 75px;
    height: 75px;

    object-fit: contain;

    border-radius: 10px;

    background-color: #ffffff;

    padding: 4px;
}
.foto-erro {
    border: 2px dashed #c9553d;
}

/* EFEITO NAS LINHAS */
.linha-monstro {
    transition: background 0.2s;
}
.linha-monstro:hover {
    background: #fff5d7;
}

/* BOTÕES */
.botao-acao {
    border: 2px solid #252525;
    border-radius: 8px;
    padding: 8px 12px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.15s, background 0.15s;
}
.botao-acao:hover {
    transform: translateY(-2px);
}
.botao-acao:active {
    transform: translateY(1px);
}


/* BOTÃO SELECIONAR */
.botao-adicionar {
    background: #65cf78;
    color: #18351d;
}
.botao-adicionar:hover {
    background: #49b85e;
}

/* BOTÃO RETIRAR */
.botao-retirar {
    background: #e47c68;
    color: #451b13;
}
.botao-retirar:hover {
    background: #d95c48;
}

/* TABELA VAZIA */
.vazio {
    padding: 25px;
    color: #777;
    font-style: italic;
}

/* MENSAGENS */
.mensagem {
    text-align: center;
    margin: 20px 0;
    font-weight: bold;
    min-height: 20px;
}
.sucesso {
    color: #65cf78;
}
.erro {
    color: #ff8270;
}

/* TELAS MENORES */

@media (max-width: 600px) {
    .cabecalho h1 {
        font-size: 26px;
    }
    .secao {
        padding: 13px;
    }
    .titulo-secao h2 {
        font-size: 20px;
    }
}
    </style>
</head>

<body>

    <header class="cabecalho">
        <h1>MONSTROBOLSO</h1>
        <p>Monte sua equipe e prepare seus monstros!</p>
    </header>

    <main class="conteudo">

        <!-- DECK DO JOGADOR -->
        <section class="secao">

            <div class="titulo-secao">
                <div>
                    <h2>Meu Deck</h2>
                    <p>Seus monstros selecionados</p>
                </div>

                <span class="contador" id="contadorDeck">0/3</span>
            </div>

            <div class="tabela-container">

                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Foto</th>
                            <th>Dano</th>
                            <th>HP</th>
                            <th>Defesa</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody id="tabelaDeck"></tbody>
                </table>

            </div>

        </section>


        <!-- MONSTROS DISPONÍVEIS -->
        <section class="secao">

            <div class="titulo-secao">
                <div>
                    <h2>Monstros disponíveis</h2>
                    <p>Escolha monstros para completar seu deck</p>
                </div>
            </div>

            <div class="tabela-container">

                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Foto</th>
                            <th>Dano</th>
                            <th>HP</th>
                            <th>Defesa</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody id="tabelaDisponiveis"></tbody>
                </table>

            </div>

        </section>

        <div id="mensagem" class="mensagem"></div>

    </main>


<script>

// Caminho do Controller
const urlController = "../Controllers/DeckController.php";

// Caminho da pasta de imagens
const pastaImagens = "imagem/";

// Referências às tabelas
const tabelaDeck = document.getElementById('tabelaDeck');

const tabelaDisponiveis = document.getElementById('tabelaDisponiveis');

const contadorDeck = document.getElementById('contadorDeck');

const mensagem = document.getElementById('mensagem');


// 1. Busca os dados no Controller
async function carregarDeck() {

    try {

        const resposta = await fetch(urlController + "?acao=listar");

        const dados = await resposta.json();

        if (!resposta.ok || !dados.ok) {
            throw new Error(dados.erro || "Erro ao carregar o deck.");
        }

        // Preenche a tabela do jogador
        montarTabela(tabelaDeck, dados.deck, "retirar");

        // Preenche a tabela dos disponíveis
        montarTabela(tabelaDisponiveis, dados.disponiveis, "adicionar");

        // Atualiza o contador
        contadorDeck.textContent = dados.deck.length + "/3";

        mensagem.textContent = "";

    } catch (erro) {

        console.error(erro);

        mensagem.textContent = erro.message;
        mensagem.className = "mensagem erro";

    }
}


// 2. Monta as tabelas utilizando os dados do JSON
function montarTabela(tabela, monstros, acao) {

    tabela.replaceChildren();

    if (!Array.isArray(monstros) || monstros.length === 0) {

        const linha = document.createElement('tr');

        const celula = document.createElement('td');

        celula.colSpan = 6;

        celula.className = "vazio";

        celula.textContent = "Nenhum monstro encontrado.";

        linha.appendChild(celula);

        tabela.appendChild(linha);

        return;
    }

    monstros.forEach(function(monstro) {

        const linha = document.createElement('tr');

        linha.className = "linha-monstro";


        // Nome
        const nome = document.createElement('td');

        nome.textContent = monstro.nome;

        nome.className = "nome-monstro";

        linha.appendChild(nome);


        // Foto
        const fotoCelula = document.createElement('td');

        const foto = document.createElement('img');

        foto.className = "foto-monstro";

        foto.alt = monstro.nome;

        foto.loading = "lazy";

        // O banco guarda somente o nome completo do arquivo
        // Exemplo: flamigato.png
        // O caminho Views/imagem/ é montado aqui
        foto.src = pastaImagens + encodeURIComponent(monstro.imagem);

        // Caso a imagem não seja encontrada
        foto.onerror = function() {

            foto.alt = "Imagem não encontrada";

            foto.classList.add("foto-erro");

        };

        fotoCelula.appendChild(foto);

        linha.appendChild(fotoCelula);


        // Dano
        const dano = document.createElement('td');

        dano.textContent = monstro.dano;

        linha.appendChild(dano);


        // HP
        const hp = document.createElement('td');

        hp.textContent = monstro.hp;

        linha.appendChild(hp);


        // Defesa
        const defesa = document.createElement('td');

        defesa.textContent = monstro.def;

        linha.appendChild(defesa);


        // Botão de ação
        const acaoCelula = document.createElement('td');

        const botao = document.createElement('button');

        botao.type = "button";

        botao.className = "botao-acao";

        if (acao === "adicionar") {

            botao.textContent = "＋ Selecionar";

            botao.classList.add("botao-adicionar");

        } else {

            botao.textContent = "－ Retirar";

            botao.classList.add("botao-retirar");

        }

        botao.addEventListener('click', function() {

            alterarDeck(acao, monstro.id);

        });

        acaoCelula.appendChild(botao);

        linha.appendChild(acaoCelula);


        // Adiciona a linha na tabela
        tabela.appendChild(linha);

    });

}


// 3. Envia a ação para o Controller
async function alterarDeck(acao, id) {

    try {

        const formulario = new FormData();

        formulario.append('id', id);

        mensagem.textContent = "Atualizando deck...";

        mensagem.className = "mensagem";


        const resposta = await fetch(

            urlController + "?acao=" + acao,

            {
                method: "POST",
                body: formulario
            }

        );

        const dados = await resposta.json();

        if (!resposta.ok || !dados.ok) {

            throw new Error(dados.erro || "Não foi possível alterar o deck.");

        }

        mensagem.textContent = "Deck atualizado!";

        mensagem.className = "mensagem sucesso";

        // Atualiza as duas tabelas
        await carregarDeck();

    } catch (erro) {

        console.error(erro);

        mensagem.textContent = erro.message;

        mensagem.className = "mensagem erro";

    }

}


// 4. Carrega os dados quando a página abre
carregarDeck();

</script>

</body>
</html>
