# Desafio | Backend

O desafio consiste em usar a base de dados em SQLite disponibilizada e criar uma **rota de uma API REST** que **liste e filtre** todos os dados. Serão 10 registros sobre os quais precisamos que seja criado um filtro utilizando parâmetros na url (ex: `/registros?deleted=0&type=sugestao`) e retorne todos resultados filtrados em formato JSON.

Você é livre para escolher o framework que desejar, ou não utilizar nenhum. O importante é que possamos buscar todos os dados acessando a rota `/registros` da API e filtrar utilizando os parâmetros `deleted` e `type`.

* deleted: Um filtro de tipo `boolean`. Ou seja, quando filtrado por `0` (false) deve retornar todos os registros que **não** foram marcados como removidos, quando filtrado por `1` (true) deve retornar todos os registros que foram marcados como removidos.
* type: Categoria dos registros. Serão 3 categorias, `denuncia`, `sugestao` e `duvida`. Quando filtrado por um `type` (ex: `denuncia`), deve retornar somente os registros daquela categoria.

O código deve ser implementado no diretorio /source. O bando de dados em formato SQLite estão localizados em /data/db.sq3.

Caso tenha alguma dificuldade em configurar seu ambiente e utilizar o SQLite, vamos disponibilizar os dados em formato array. Atenção: dê preferência à utilização do banco SQLite.

Caso você já tenha alguma experiência com Docker ou queira se aventurar, inserimos um `docker-compose.yml` configurado para rodar o ambiente (utilizando a porta 8000).

Caso ache a tarefa muito simples e queira implementar algo a mais, será muito bem visto. Nossa sugestão é implementar novos filtros (ex: `order_by`, `limit`, `offset`), outros métodos REST (`GET/{id}`, `POST`, `DELETE`, `PUT`, `PATCH`), testes unitários etc. Só pedimos que, caso faça algo do tipo, nos explique na _Resposta do participante_ abaixo.

# Resposta do participante
_Responda aqui quais foram suas dificuldades e explique a sua solução_

O teste foi bem condizente com o que eu venho estudado e trabalhado com o PHP durante minha experiencia profissional. Tive apenas pequenas dificuldades em criar o ambiente de testes unitarios e o ambiente de desenvolvimento na minha maquina pessoal Windows, estava acostumado com Linux.

Eu decidi criar a API REST utilizando o framework Laravel pois achei mais simples de fazer já que o laravel me permite criar a API de uma maneira mais rapida, eficiente e intuitiva, também estou familiarizado com a arquitetura MVC. 

Primeiramente eu criei o projeto laravel dentro da pasta source do teste;
Editei o arquivo .env para configurar a conexão com o banco de dados sqlite fornecido na pasta data, editando essas duas linhas e removendo as linhas desnecessarias:

    //DB_CONNECTION=sqlite
    //DB_DATABASE=/data/db.sq3

Editei o arquivo VerifyCsrfToken.php para ignorar o CsrfToken das rotas utilizadas, para fins de teste.

Depois foi criada a Model e o Controller responsavel por gerenciar a tabela registro;

    app/Models/Registro.php
    app/Http/Controllers/RegistroController.php

Criei as rotas no arquivo /routes/web.php
Implementei o codigo do controller e da model, foram criados os metodos REST `GET`, `GET/{id}`, `POST`, `DELETE` e `PUT`; E os filtros utilizando os parametros `deleted`, `type`,`order_by`, `limit` e `offset`. 
Ao analizar a estrutura dos dados na tabela do banco de dados, verifiquei que os campos `whistleblower_name` e `whistleblower_birth` só sao preenchidos quando o campo `is_identified` for true, então foi implementado essa logica também no controller, esses campos so sao permitidos inserir e editar se o campo `is_identified` for true.

Uma documentação detalhada da API esta disponivel na raiz do desafio, arquivo `Documentacao.pdf`.

Eu alterei o campo id do banco de dados para ser autoincrementado quando fosse adicionado um novo campo.

Após tudo estar funcionando, parti para os testes unitários;
Criei o RegistroFactory e editei para gerar registros de acordo com a estrutura da tabela;

    /database/factories/RegistroFactory.php

Criei a classe de teste RegistroControllerTest.php e implementei os testes;

    /tests/Feature/RegistroControllerTest.php

Foram criados 6 testes unitarios basicos no total, tanto para passar ou falhar.

Foi necessario criar um banco sqlite de teste e o arquivo .env.testing para especificar o banco de testes 
o arquivo sqlite de teste esta no diretorio /database/db_teste.sq3

Os testes podem ser rodados com o comando 'php artisan test' na pasta raiz do projeto

Por ultimo editei os arquivos docker-compose.yml e o Dockerfile para rodar essa aplicação laravel de maneira mais facil, basta iniciar o container com o comando 'docker-compose up' e a aplicação estara rodando na porta 8000 do localhost

Se for rodar localmente será necessario alterar o caminho do banco de dados sqlite nos arquivos .env e .env.testing

Desafio concluido por Felipe Cecconello Fontana
Obrigado pela oportunidade!!! 
