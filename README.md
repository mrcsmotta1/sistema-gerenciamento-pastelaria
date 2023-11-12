

Rendered
Projeto Sistema de Gerenciamento de Pastelaria

O Sistema de Gerenciamento de Pastelaria é um projeto que oferece uma solução abrangente para a administração eficiente de pastelarias. Desenvolvido em Laravel, um popular framework PHP, o sistema inclui funcionalidades como o cadastro e gestão de clientes, controle de pedidos, gerenciamento de produtos e tipos de produtos, além de ferramentas de relatórios e análises. O objetivo é proporcionar uma experiência intuitiva e eficaz para os negócios no ramo de pastelaria, otimizando processos e facilitando a tomada de decisões. Este projeto é personalizável e adaptável às necessidades específicas de cada pastelaria, promovendo uma gestão simplificada e eficiente do negócio.
Funcionalidades Principais

    Cadastro e gestão de clientes
    Controle eficiente de pedidos
    Gerenciamento de produtos e tipos de produtos
    Relatórios e análises

Setup inicial

    Após realizar o clone do projeto, instale as dependências do mesmo com:


docker run --rm -itv $(pwd):/app -w /app -u $(id -u):$(id -g) composer:2.5.8 install

    Com as dependências instaladas, crie o arquivo de configuração .env:

cp .env.example .env

    Inicie o ambiente Docker executando:

docker compose up -d

    Dê permissões ao usuário correto para escrever logs na aplicação

docker compose exec app chown -R www-data:www-data /app/storage

    Garanta que o contêiner de banco de dados está de pé. Os logs devem exibir a mensagem ready for connections nas últimas linhas

docker compose logs database

Aguarde até que o comando acima tenha como uma das últimas linhas a mensagem ready for connections.

    Para criar o banco de dados, execute:

docker compose exec app php artisan migrate --seed

    Para criar pasta de imagens, execute:

docker compose exec app php artisan storage:link

    Para envio de email de Pedidos, execute:

docker compose exec app php artisan queue:work --tries=2 --delay=10

Obs:

    Para executar os testes, utilize o seguinte comando:

composer test

    Note: A index do projeto pode ser acessado através do endereço http://localhost:8123

    Note: API já estará acessível através do endereço http://localhost:8123/api

    Note: Além disso, o endereço http://localhost:8025 provê acesso ao serviço de e-mail Mailpit

Documentação das rotas:

Swagger
Executando requisições com o Postman:

Postman

Run in Postman
Suggestions for a good README
