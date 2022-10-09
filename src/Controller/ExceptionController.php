<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request; 
use Slim\Psr7\Response;

class ExceptionController extends Controller {
    public function notFound(Request $request){ 
        $response = new Response;
        return $this->render($response, '404.html');
    } 
}
?>