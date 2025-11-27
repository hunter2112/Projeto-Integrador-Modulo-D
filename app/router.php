<?php

namespace App;

class Router
{
    // Armazena todas as rotas registradas.
    // Estrutura:
    // [
    //    'GET' => ['/caminho' => callback],
    //    'POST' => ['/caminho' => callback]
    // ]
    private array $routes = [];
    
    /*
     |-------------------------------------------------------------
     | Registra uma rota do tipo GET
     | Ex: $router->get('/home', function() { ... });
     |-------------------------------------------------------------
    */
    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }
    
    /*
     |-------------------------------------------------------------
     | Registra uma rota do tipo POST
     | Ex: $router->post('/login', [UserController::class, 'login']);
     |-------------------------------------------------------------
    */
    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }
    
    /*
     |-------------------------------------------------------------
     | resolve()
     | Recebe a URL atual da requisição
     | Procura uma rota correspondente
     | Executa a função associada a essa rota
     |-------------------------------------------------------------
    */
    public function resolve(): mixed
    {
        // Método HTTP (GET, POST, PUT, DELETE etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Caminho acessado na URL
        // Exemplo: "/cadastro?pagina=2" → pega "/cadastro"
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = explode('?', $path)[0];
        
        // Procura na lista de rotas registradas
        $callback = $this->routes[$method][$path] ?? null;
        
        // Se a rota não foi encontrada → 404
        if ($callback === null) {
            http_response_code(404);
            return "404 Not Found";
        }
        
        /*
         |---------------------------------------------------------
         | Se a rota está no formato:
         |    [NomeDaClasse::class, 'metodo']
         | Então é um controller
         |---------------------------------------------------------
        */
        if (is_array($callback)) {
            [$class, $method] = $callback;

            // Cria a instância do controller
            $controller = new $class();

            // Executa o método indicado
            return $controller->$method();
        }
        
        /*
         |---------------------------------------------------------
         | Caso contrário, é uma função anônima (callable)
         | Ex: function() { echo "Home"; }
         |---------------------------------------------------------
        */
        return $callback();
    }
}
