# Configurações

## Instalação e Configuração

1. Clone o repositório:

    ```bash
    git clone git@github.com:smart-creative-solutions/site-back.git
    ```

2. Entre no diretório do projeto:

    ```bash
    cd project
    ```

3. Copie o arquivo de exemplo de configuração:

    ```bash
    cp .env.exemaple .env
    ```

4. Configure as variáveis de ambiente no arquivo `.env`:

    ```env
    DB_PASSWORD=root
    ```

5. Inicie os containers Docker:

    ```bash
    docker compose up -d
    ```

6. Acesse o container da aplicação:

    ```bash
    docker compose exec app bash
    ```

7. Instale as dependências do Composer:

    ```bash
    composer install
    ```

8. Gere a chave da aplicação:

    ```bash
    php artisan key:generate
    ```

9. Execute as migrações do banco de dados:

    ```bash
    php artisan migrate
    ```

10. Configure o CaptainHook no container:

    ```bash
    docker compose exec app bash
    ```

    ```bash
    vendor/bin/captainhook install --run-mode=docker --run-exec="docker exec -i site-back-app-1"
    ```

    Durante a instalação, responda às perguntas conforme indicado:

    - **Install commit-msg hook?** → `Y`
    - **Install pre-push hook?** → `Y`
    - **Install pre-commit hook?** → `Y`
    - **Install prepare-commit-msg hook?** → `Y`
    - **Install post-commit hook?** → `n`
    - **Install post-merge hook?** → `n`
    - **Install post-checkout hook?** → `n`
    - **Install post-rewrite hook?** → `n`
