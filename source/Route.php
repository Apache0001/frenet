<?php

namespace Source;

class Route
{
    protected  $route;

    protected  $getInput;

    protected $httpMethod;

    public function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function get(string $route, $handler)
    {
        $this->addRoute('GET', $route, $handler);
    }

    public function post(string $route, $handler)
    {
        $this->addRoute("POST", $route, $handler);
    }

    /**
     * addRoute
     *
     * @param string $method
     * @param string $route
     * @param string $handler
     * @return void
     */
    public function addRoute(string $method, string $route, $handler)
    {
        $input = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->getInput = ((empty($input)) ? "/" : $input);

        $this->route[] = [
            $method => [
                $route => [
                        "route" => $route,
                        "controller" => (!is_string($handler) ? $handler : strstr($handler, ":", true)),
                        "method" => (!is_string($handler)) ? : str_replace(":" , "", strstr($handler, ":", false))
                ] 
            ]   
        ];
    }

    public function dispatch(): void
    {
       
        $this->route = null;

       foreach($this->routes[$this->httpMethod] as $key => $route){
           $this->route = $route;
       }
        

        if($route){
            var_dump($route);
        }else{
            echo 'errpr';
        }
    }

    public function routes(): ?array
    {
        return $this->route;
    }

    public function namespace(): ?string
    {
        return "Source\App\Controllers\\";
    }
}