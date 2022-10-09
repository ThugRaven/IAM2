<?php

namespace App\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Medoo\Medoo;

class LabController extends Controller{
    public function start(Request $request, Response $response){
        return $this->render($response, 'start.html');
    }
    public function ex1(Request $request, Response $response){
        return $this->render($response, 'ex1.html');
    }
    public function ex2(Request $request, Response $response){
        return $this->render($response, 'ex2.html');
    }
    public function ex3(Request $request, Response $response){
        return $this->render($response, 'ex3.html');
    }
    public function ex4(Request $request, Response $response){
        return $this->render($response, 'ex4.html');
    }
    public function ex5(Request $request, Response $response){
        return $this->render($response, 'ex5.html');
    }
}

?>