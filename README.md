# Configurações

## Instalação e Configuração

1. Clone o repositório:

    ```bash
    git clone git@github.com:davidmarquescoder/laravel-12-setup.git
    ```

2. Entre no diretório do projeto:

    ```bash
    cd project
    ```

3. Copie o arquivo de exemplo de configuração:

    ```bash
    cp .env.example .env
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
    vendor/bin/captainhook install --run-mode=docker --run-exec="docker exec -i <NomeContainerDoApp>"
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

## Configuração de Autenticação

> **💡 Autenticação Pronta!**  
> Este setup inclui um comando personalizado que gera toda a estrutura de autenticação. Se você deseja utilizar nosso modelo de autenticação com Laravel Sanctum e Sessão, siga as instruções abaixo. Caso prefira implementar sua própria solução de autenticação, sinta-se à vontade para pular esta seção.

11. Gere a estrutura de autenticação:

    ```bash
    php artisan make:auth
    ```

    Este comando criará toda a estrutura necessária para autenticação. Após executá-lo, você precisará realizar as seguintes configurações:

### Configuração do Laravel Sanctum

12. Configure os domínios stateful no arquivo `config/sanctum.php`:

    Localize a chave `stateful` e substitua por:

    ```php
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:3000,::1',
        Sanctum::currentApplicationUrlWithPort(),
        env('FRONTEND_URL') ? ','.parse_url(env('FRONTEND_URL'), PHP_URL_HOST) : ''
    ))),
    ```

13. Adicione as variáveis de ambiente no arquivo `.env`:

    ```env
    SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
    FRONTEND_URL=http://localhost:3000
    ```

### Configuração do CORS

14. Habilite o suporte a credenciais no arquivo `config/cors.php`:

    ```php
    'supports_credentials' => true,
    ```

### Configuração de Middlewares e Exceções

15. Configure os middlewares no arquivo `bootstrap/app.php`:

    No método `withMiddleware`, adicione:

    ```php
    $middleware->statefulApi();
    $middleware->redirectGuestsTo(fn (Request $request) => route('api.auth.store'));
    ```

16. Configure o tratamento de exceções de autenticação no arquivo `bootstrap/app.php`:

    No método `withExceptions`, adicione:

    ```php
    $exceptions->render(function (AuthenticationException $e, Request $request) {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
    });
    ```

    > **Nota:** Não esqueça de importar as classes necessárias no topo do arquivo:
    > - `use Illuminate\Http\Request;`
    > - `use Illuminate\Auth\AuthenticationException;`
    > - `use Symfony\Component\HttpFoundation\Response;`


php artisan install:api

php artisan install:broadcasting
composer require laravel/reverb --with-all-dependencies
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
composer require --dev captainhook/captainhook
composer require laravel/pint --dev