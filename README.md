  # Projeto _Sistema de Gerenciamento de Pastelaria_


O Sistema de Gerenciamento de Pastelaria é um projeto que oferece uma solução abrangente para a administração eficiente de pastelarias. Desenvolvido em Laravel, um popular framework PHP, o sistema inclui funcionalidades como o cadastro e gestão de clientes, controle de pedidos, gerenciamento de produtos e tipos de produtos, além de ferramentas de relatórios e análises. O objetivo é proporcionar uma experiência intuitiva e eficaz para os negócios no ramo de pastelaria, otimizando processos e facilitando a tomada de decisões. Este projeto é personalizável e adaptável às necessidades específicas de cada pastelaria, promovendo uma gestão simplificada e eficiente do negócio.

## Funcionalidades Principais

- Cadastro e gestão de clientes
- Controle eficiente de pedidos
- Gerenciamento de produtos e tipos de produtos
- Relatórios e análises


## Setup inicial

1. Após realizar o clone do projeto, instale as dependências do mesmo com:
```shell

docker run --rm -itv $(pwd):/app -w /app -u $(id -u):$(id -g) composer:2.5.8 install
```

2. Com as dependências instaladas, crie o arquivo de configuração `.env`:
```shell
cp .env.example .env
```

3. Inicie o ambiente _Docker_ executando:
```shell
docker compose up -d
```

4. Dê permissões ao usuário correto para escrever logs na aplicação
```shell
docker compose exec app chown -R www-data:www-data /app/storage
```

5. Garanta que o contêiner de banco de dados está de pé. Os logs devem exibir a mensagem _ready for connections_ nas últimas linhas
```shell
docker compose logs database
``` 
Aguarde até que o comando acima tenha como uma das últimas linhas a mensagem _ready for connections_.

6. Para criar o banco de dados, execute:
```shell
docker compose exec app php artisan migrate --seed
```

7. Para criar pasta de imagens, execute:
```shell
docker compose exec app php artisan storage:link
```

8. Para envio de email de Pedidos, execute:
```shell
docker compose exec app php artisan queue:work --tries=2 --delay=10
```

### Obs:

> **Para executar os testes, utilize o seguinte comando:**

```shel
composer test
```

> Note: A index do projeto pode ser acessado através do endereço http://localhost:8123

> Note: API já estará acessível através do endereço http://localhost:8123/api

> Note:  Além disso, o endereço http://localhost:8025 provê acesso ao serviço de e-mail _Mailpit_
   

### Documentação das rotas:

[Swagger](http://localhost:8123/api/documentation)

### Executando requisições com o Postman:

[Postman](https://documenter.getpostman.com/view/2333553/SztA78vK)

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/2333553-a078f7b7-efcf-4da3-9cee-51e7e4c241dd?action=collection%2Ffork&source=rip_markdown&collection-url=entityId%3D2333553-a078f7b7-efcf-4da3-9cee-51e7e4c241dd%26entityType%3Dcollection%26workspaceId%3D6c8de743-d5d4-44e7-8c4f-3ebc99acd120)
