# amparoserver - Sistema de Gestão de Atividades Culturais

Este projeto é um sistema de gestão de atividades culturais desenvolvido com um back-end em **Laravel 11** e um front-end em **React**. Ele permite o cadastro de alunos, responsáveis, categorias, turmas e aulas, além de gerar contratos em PDF e gerenciar a presença dos alunos.

## Estrutura do Projeto

O projeto está organizado em duas pastas principais:

*   **`api-amparoserver`:** Contém o código-fonte da API em Laravel.
*   **`frontend-react-js`:** Contém o código-fonte da aplicação React.


## Back-end (Laravel)

### Requisitos

*   PHP >= 8.1
*   Composer
*   MySQL (ou outro banco de dados suportado pelo Laravel)
*   XAMPP ou ambiente de servidor web similar

### Instalação

1.  **Clonar o Repositório:**
    ```bash
    git clone <URL do repositório> amparoserver
    cd amparoserver/backend
    ```

2.  **Instalar Dependências:**
    ```bash
    composer install
    ```

3.  **Configurar o Ambiente:**
    *   Copie o arquivo `.env.example` para `.env`:
        ```bash
        cp .env.example .env
        ```
    *   Edite o arquivo `.env` e configure as variáveis de ambiente, principalmente as relacionadas ao banco de dados:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=<nome_do_banco_de_dados>
        DB_USERNAME=<usuário_do_banco_de_dados>
        DB_PASSWORD=<senha_do_banco_de_dados>

        APP_URL=[http://amparoserver.test](http://amparoserver.test) # Se usar Virtual Host
        # APP_URL=http://localhost # Se usar localhost diretamente

        SESSION_DOMAIN=.amparoserver.test # Se usar Virtual Host
        # SESSION_DOMAIN=localhost # Se usar localhost diretamente
        ```

4.  **Gerar a Chave da Aplicação:**
    ```bash
    php artisan key:generate
    ```

5.  **Executar as Migrations:**
    ```bash
    php artisan migrate
    ```

6.  **Criar um Usuário Fixo (Seeder):**
    ```bash
    php artisan db:seed --class=UsersTableSeeder
    ```
    Isso criará um usuário com email `admin@example.com` e senha `password`.

7.  **Criar o Link Simbólico para o Storage:**
    ```bash
    php artisan storage:link
    ```

8.  **Configurar o Virtual Host (Recomendado):**

    *   Edite o arquivo `httpd-vhosts.conf` do Apache (geralmente em `C:\xampp\apache\conf\extra\httpd-vhosts.conf`).
    *   Adicione a seguinte configuração, ajustando os caminhos conforme necessário:

    ```apache
    <VirtualHost *:80>
        ServerAdmin admin@amparoserver.test
        DocumentRoot "C:/xampp/htdocs/amparoserver/backend/public"
        ServerName amparoserver.test

        <Directory "C:/xampp/htdocs/amparoserver/backend/public">
            Options Indexes FollowSymLinks Includes ExecCGI
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    ```

    *   Edite o arquivo `hosts` do Windows (geralmente em `C:\Windows\System32\drivers\etc\hosts`).
    *   Adicione a seguinte linha:

    ```
    127.0.0.1       amparoserver.test
    ```

    *   Reinicie o Apache.

9.  **Iniciar o Servidor:**

    *   **Com Virtual Host:** Apenas inicie o Apache no XAMPP.
    *   **Sem Virtual Host:** Execute `php artisan serve` na raiz do projeto `backend`.

### Rotas da API

*   **`POST /api/login`:**  Autenticação de usuário.
*   **`POST /api/logout`:**  Logout do usuário (requer autenticação).
*   **`GET /api/user`:** Retorna os dados do usuário autenticado (requer autenticação).
*   **`GET /api/dashboard`:** Retorna dados para o dashboard (requer autenticação).
*   **`GET /api/students/index`:**  Lista de alunos (requer autenticação).
*   **`POST /api/student/create`:** Cria um novo aluno (requer autenticação).
*   **`GET /api/students/{id}/contract`:** Gera o contrato de um aluno específico (requer autenticação).
*   **`GET /api/categories`:** Lista de categorias (requer autenticação).

## Front-end (React)

### Requisitos

*   Node.js >= 18.x.x
*   npm >= 8.x.x

### Instalação

1.  **Navegue até a pasta `frontend-react-js`:**
    ```bash
    cd ../frontend-react-js
    ```

2.  **Instalar Dependências:**
    ```bash
    npm install
    ```

3.  **Configurar Variáveis de Ambiente:**
    *   Crie um arquivo `.env` na raiz da pasta `frontend-react-js`.
    *   Defina a variável `REACT_APP_API_URL` com a URL da sua API Laravel:

        ```
        REACT_APP_API_URL=[http://amparoserver.test/api](http://amparoserver.test/api)
        ```

        Ou, se não estiver usando o Virtual Host:

        ```
        REACT_APP_API_URL=http://localhost/api-amparoserver/public/api
        ```

4.  **Iniciar a Aplicação:**
    ```bash
    npm start
    ```

### Componentes Principais

*   **`App.js`:**  Componente principal que gerencia o estado da aplicação, a autenticação e as rotas.
*   **`components/Login/Login.js`:** Componente de login.
*   **`components/Dashboard/Dashboard.js`:** Componente do dashboard.
*   **`components/NavBar/SideBar.js`:**  Componente da barra lateral de navegação.
*   **`components/Student/ListStudent/ListStudent.js`:** Componente para listar os alunos.
*   **`components/Student/AddStudent/AddStudent.js`:**  Componente para adicionar novos alunos.
*   **`components/TestComponent.js`:** Componente de teste para comunicação com a API.
*   **`services/api.js`:**  Configuração do cliente `axios` para comunicação com a API.

## Direitos Autorais

Copyright © 2025 Renan Felix de Souza. Todos os direitos reservados.

Este projeto é privado e seu código-fonte não pode ser copiado, distribuído, modificado ou utilizado para qualquer fim sem a permissão explícita por escrito do proprietário dos direitos autorais.

## Contato

Para quaisquer dúvidas ou solicitações de permissão, entre em contato através do email: renanfdev@gmail.com (mailto:renanfdev@gmail.com).

