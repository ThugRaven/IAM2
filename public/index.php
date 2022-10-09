<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathMiddleware;
use DI\Container;
use Medoo\Medoo;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$container->set('templating', function(){
    return new Mustache_Engine([
        'loader' => new Mustache_Loader_FilesystemLoader(
            __DIR__ . '/../templates',
            ['extension' => '']
        )
    ]);
});

$container->set('database', function(){
    $pdo = new PDO('mysql:dbname=framework;host=127.0.0.1', 'root', 'root');
    $db = new Medoo([
        'pdo' => $pdo,
        'type' => 'mysql'
    ]);

    return $db;  
});

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));

$app->get('/hello/{name}', function(Request $request, Response $response, array $args = []){
    $html = $this->get('templating')->render('hello.html', [
        'name' => $args['name']
    ]);
    $response->getBody()->write($html);
    return $response;
});

$app->get('/start', '\App\Controller\LabController:start');
$app->get('/install', '\App\Controller\DatabaseController:install');
$app->redirect('/', '/Slim/start', 301);

$app->get('/get/cat', '\App\Controller\DatabaseController:getCat');
$app->get('/get/msg', '\App\Controller\DatabaseController:getMsg');
$app->post('/set/msg', '\App\Controller\DatabaseController:setMsg');
$app->delete('/del/msg/{id}', '\App\Controller\DatabaseController:delMsg');

$app->get('/ex1', '\App\Controller\LabController:ex1');
$app->get('/ex2', '\App\Controller\LabController:ex2');
$app->get('/ex3', '\App\Controller\LabController:ex3');
$app->get('/ex4', '\App\Controller\LabController:ex4');
$app->get('/ex5', '\App\Controller\LabController:ex5');

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function (Psr\Http\Message\ServerRequestInterface $request) use ($container){
    $controller = new App\Controller\ExceptionController($container);
    return $controller->notFound($request); 
});

$app->run();

?>
