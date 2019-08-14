<?php
header("Access-Control-Allow-Origin: *");

/**
 * Class Router
 * @method post(string $string, Closure $param)
 * @method get(string $string, Closure $param)
 */
class Router
{
    /* armazenamento das rotas e dos parâmetros atuais do HTTP */
    /**
     * @var array
     */
    private static $params = [];
    private static $files = [];
    /**
     * @var array
     */
    private $routes = [];

    /**
     *  Getter para que controlador possa pegar os dados da requisição do cliente [$_FILES]
     * @return array
     */
    public static function getRequestFile()
    {
        return self::$files;
    }

    /**
     *  Getter para que controlador possa pegar os dados da requisição do cliente [$_GET | $_POST]
     * @return array
     */
    public static function getRequest()
    {
        return self::$params;
    }

    public function debugger()
    {
        return json_encode($this->routes);
    }

    /**
     * Chamado sempre que existe uma chamada nessa classe, caso a chamada
     * seja válida, irá separar e validar os argumentos recebidos, devendo receber
     * uma string e uma função. E por fim armazena no array de rotas, com a estrutura:
     * array(2) {
     * ["get"]=> array(3) {
     * ["/"]=> object(Closure)#2 (0) { }
     * ...
     *  }
     * ["post"]=> array(1) {
     * ["/list"]=> object(Closure)#5 (0) { }
     * ...
     * }
     * }
     * @param string $method
     * @param array $args
     * @return bool
     */
    public function __call($method, array $args)
    {
        $method = strtolower($method);
        if (!$this->validate($method))
            return false;
        //[$route, $action] = $args;
        $route = $args[0];
        $action = $args[1];
        if (!isset($action) or !is_callable($action))
            return false;

        $this->routes[$method][$route] = $action;
        return true;
    }

    /**
     * verifica se método chamado é GET ou POST
     * @param string $method
     * @return bool
     */
    private function validate($method)
    {
        return in_array($method, ['get', 'post']);
    }

    /**
     * Dá início a aplicação, verificando se existem rotas
     * com o método HTTP atual (post ou get), se existe a rota definida pelo
     * parâmetro GET r. E por fim chamando o callable da rota correspondente,
     * finalizando a aplicação exibindo o seu retorno (a resposta do Controller).
     */
    public function run()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
        $route = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';//$_SERVER['REQUEST_URI']
        !isset($this->routes[$method]) && die('405 Method not allowed');
        !isset($this->routes[$method][$route]) && die('404 Error');
        self::$params = $this->getParams($method);
        self::$files = $this->getFiles();
        die($this->routes[$method][$route]());
    }

    /**
     *  Pega as variáveis correspondente ao método atual, sendo os dados
     * enviados pelo cliente.
     * @param string $method
     * @return mixed
     */
    private function getParams($method)
    {
        if ($method == 'get')
            return $_GET;
        return ($_POST === null) ? json_decode(
            file_get_contents("php://input"),
            true
        )
            : $_POST;
    }

    /**
     * Acessa a variavel global $_FILES
     * enviados pelo cliente.
     */
    private function getFiles()
    {
        return $_FILES ? $_FILES : null;
    }
}