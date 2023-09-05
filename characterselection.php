<!DOCTYPE html>
<html>
<head>
	<title>Perfil do jogador</title>
	<link rel="stylesheet" type="text/css" href="cselection2.css">
  

</head>
<body>
<img id="mybottom" src="bottomreal.png" alt="Minha Imagem">
<img id="logout" src="botoes.png" alt="Logout">
<img id="ladder" src="botoes.png" alt="ladder">
<img id="quick" src="botoes.png" alt="quick">
<img id="pb" src="botoes.png" alt="pb">
<img id="avatar" src="zoro.png" alt="avatar">

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



		// Query para buscar as informações do jogador
		$sql = "SELECT * FROM player WHERE id = 1"; // Substitua o valor '1' pelo ID do jogador desejado
		$result = mysqli_query($conn, $sql);
		
		// Verificação se a query retornou resultados
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			// Aqui você pode exibir os dados do jogador
		} else {
			echo "Jogador não encontrado";
		}

// Consulta SQL para obter os caminhos das imagens
$sql_imagens = "SELECT photo, character_name, description, skills FROM characters ORDER BY id";
$resultado_imagens = mysqli_query($conn, $sql_imagens);
$maisPersonagens = mysqli_num_rows($resultado_imagens) > $indiceInicio + $personagensPorPagina;


$sql_habilidades = "SELECT id, photo_skill FROM skills";
$resultado_habilidades = mysqli_query($conn, $sql_habilidades);

$fotos_habilidades = array();
  while ($linha = mysqli_fetch_assoc($resultado_habilidades)) {
    $fotos_habilidades[] = $linha['photo_skill'];
  }

$sql_haki = "SELECT id, haki_photo FROM haki";
$resultado_haki = mysqli_query($conn, $sql_haki);


	// Fechamento da conexão com o banco de dados
	mysqli_close($conn);
?>
<ul>
	<li><?php echo strtoupper($row['username']); ?></li>
	<li><?php echo strtoupper($row['class']); ?></li>
	<li>CLAN: <?php echo strtoupper($row['cla']); ?></li>
	<li>LEVEL: <?php echo strtoupper($row['lvl']); ?></li>
	<li>LADDERANK: <?php echo strtoupper($row['ranking']); ?></li>
	<li>RATIO: <?php echo strtoupper($row['wins']) . ' - ' . strtoupper($row['loss']); ?></li>
</ul>
<p class="plogout"> LOGOUT </p>
<p class="pladder"> START LADDER GAME </p>
<p class="pquick"> START QUICK GAME </p>
<p class="ppb"> START PRIVATE GAME </p>
<p class="select3"> SELECT 3 CHARACTERS INTO YOUR TEAM </p>

<table>
	<tr>
		<?php
			$contador = 0;
			while ($linha = mysqli_fetch_assoc($resultado_imagens)) {
				if ($contador == 21) {
					break;
				}
				echo '<td><a href="#" class="personagem" data-nome="' . $linha['character_name'] . '" data-descricao="' . $linha['description'] . '" data-photo="' . $linha['photo'] . '" data-pskills="' . $linha['photo'] . '" data-skills="' . $linha['skills'] . '"><img src="' . $linha['photo'] . '" width="80" height="77"></a></td>';
				$contador++;
				if ($contador % 7 == 0 && $contador <= 21) {
					echo '</tr><tr>';
				}
			}
		?>
	</tr>
</table>

<a href="#" id="btn-next" class="btn-next">Próxima Página</a>


<table>
  <tr>
    <?php
      $contador = 0;
      while ($sphoto = mysqli_fetch_assoc($resultado_habilidades))  {
        if ($contador == 4) { // Verifica se já exibiu 4 habilidades e encerra o loop
          break;
        }
        echo '<td><img src="' . $sphoto['photo_skill'] . '" width="80" height="77"></td>';
        $contador++;
      }
    ?>
  </tr>
</table>

    <div class="card">
<div id="modal-personagem" style="display:none;">
<img id="img-per">
<img id="img-skill">
  <h2 id="nome-personagem"></h2>
  <p id="descricao-personagem"></p>
  <ul id="data-skills"></ul>
</div>
    </div>


<table class="coluna">
  <tr>
    <td><img src="personagem22.jpg" width="80" height="77"></td>
  
    <td><img src="personagem23.jpg" width="80" height="77"></td>

    <td><img src="personagem24.jpg" width="80" height="77"></td>
  </tr>
</table>

<script>
  var paginaAtual = 0; // Mantém o controle da página atual

  function exibirPersonagens(personagens) {
    var tabela = document.getElementById("personagens-table");
    tabela.innerHTML = ""; // Limpa a tabela antes de exibir os personagens

    for (var i = 0; i < personagens.length; i++) {
      var personagem = personagens[i];
      var coluna = document.createElement("td");
      var imagem = document.createElement("img");
      imagem.src = personagem.photo;
      imagem.width = 80;
      imagem.height = 77;
      coluna.appendChild(imagem);
      tabela.appendChild(coluna);
    }
  }

  // Função para carregar os próximos personagens
  function carregarProximosPersonagens() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var personagens = JSON.parse(xhr.responseText);
        exibirPersonagens(personagens);
        paginaAtual++; // Incrementa a página atual
      }
    };

    var personagensPorPagina = <?php echo $personagensPorPagina; ?>;
    var indiceInicio = paginaAtual * personagensPorPagina;
    xhr.open('GET', 'carregar_proximos_personagens.php?indiceInicio=' + indiceInicio + '&personagensPorPagina=' + personagensPorPagina, true);
    xhr.send();
  }

  // Chama a função para carregar os primeiros personagens
  carregarProximosPersonagens();

  // Adiciona evento de clique ao botão "Próxima Página"
  var btnNext = document.getElementById('btn-next');
  btnNext.addEventListener('click', carregarProximosPersonagens);
</script>

<script> 
   // Captura todos os elementos da classe "personagem" e adiciona um evento de clique em cada um deles
 var personagens = document.querySelectorAll('.personagem');
 for (var i = 0; i < personagens.length; i++) {
   personagens[i].addEventListener('click', function(event) {
     event.preventDefault();
     // Exibe o modal de personagem
     var modal = document.getElementById('modal-personagem');
     modal.style.display = 'block';
     // Atualiza as informações do modal com os dados do personagem clicado
     document.getElementById('nome-personagem').textContent = this.getAttribute('data-nome');
     document.getElementById('descricao-personagem').textContent = this.getAttribute('data-descricao');
     document.getElementById('img-per').src=this.getAttribute('data-photo');
     document.getElementById('img-skill').src=this.getAttribute('data_pskills');
     var skills = this.getAttribute('data-skills').split(',');
     var listaskills = document.getElementById('skills-personagem');
     listaskills.innerHTML = '';
     for (var j = 0; j < skills.length; j++) {
       var item = document.createElement('li');
       item.class="list-group-item";
       item.textContent = skills[j];
       listaskills.appendChild(item);
       
     }
   });
 }
  </script>

</body>
</html>