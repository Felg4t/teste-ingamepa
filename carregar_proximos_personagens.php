<?php
// Conexão com o banco de dados
$conn = mysqli_connect('localhost', 'Felgat', '123456', 'pirate_arena');
// Verificação da conexão
if (!$conn) {
    die('Erro ao conectar ao banco de dados: ' . mysqli_connect_error());
}

$personagensPorPagina = 21; // Número de personagens exibidos por página

// Verifica se o parâmetro "pagina" foi passado na URL
if (isset($_GET['pagina'])) {
  // Converte o valor da página para um número inteiro
  $pagina = intval($_GET['pagina']);
} else {
  // Página padrão é a primeira página
  $pagina = 1;
}

// Calcula o índice de início com base na página atual
$indiceInicio = ($pagina - 1) * $personagensPorPagina;

// Query para buscar as informações dos personagens
$sql = "SELECT photo, character_name, description, skills FROM characters ORDER BY id LIMIT $indiceInicio, $personagensPorPagina";
$resultado = mysqli_query($conn, $sql);

// Verificação se a query retornou resultados
if (mysqli_num_rows($resultado) > 0) {
    // Loop para exibir os personagens
    while ($linha = mysqli_fetch_assoc($resultado)) {
        if ($linha['photo'] != "") {
            $nome_personagem = $linha['character_name'];
            $descricao_personagem = $linha['description'];
            $skills_personagem = $linha['skills'];
            $path_imagem = $linha['photo'];

            echo '<div class="column">';
            echo '<img src="' . $path_imagem . '" alt="' . $nome_personagem . '" style="width:100%" onclick="openModal(this,\'' . $nome_personagem . '\',\'' . $descricao_personagem . '\',\'' . $skills_personagem . '\')">';
            echo '<div class="desc">' . $nome_personagem . '</div>';
            echo '</div>';
        }
    }
}

// Fechamento da conexão com o banco de dados
mysqli_close($conn);
?>
