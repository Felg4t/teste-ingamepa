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