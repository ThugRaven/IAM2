<?php

namespace App\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Medoo\Medoo;

class DatabaseController extends Controller{
    public function getCat(Request $request, Response $response){
        $data = $this->connect()->select("cat", [
			"cat_name",
			"cat_id"
		]);
		return $response->withStatus(200)->withJson($data);
    }
    public function getMsg(Request $request, Response $response){
        $data = $this->connect()->select("msg", [
			"msg_msg",
			"msg_date",
            "msg_time",
			"msg_id",
			"msg_author"],[
			"ORDER" => "msg_time",
            "ORDER" => [
                "msg_time" => "DESC"
            ]
		]);
		return $response->withStatus(200)->withJson($data);
    }
    public function setMsg(Request $request, Response $response, array $args){
        $msg = $request->getParam('msg');
        $username = $request->getParam('username');
                
        $data = $this->connect()->insert("msg", [
			"msg_msg" => $msg,
			"msg_author" => $username,
			"msg_date" => date('Y-m-d'),
            "msg_time" => date('Y-m-d H:i:s')
		]);
		return $response->withStatus(200)->withJson($data);
    }
    public function delMsg(Request $request, Response $response, array $args){
        $id = $args['id'];
        $data = $this->connect()->delete("msg", [
			"msg_id" => $id
		]);	
        return $response->withStatus(200)->withJson($data);
    }
    


    public function install(Request $request, Response $response){
		$query = $this->connect()->pdo->prepare('CREATE TABLE IF NOT EXISTS `cat` ( `cat_id` INT NOT NULL AUTO_INCREMENT , `cat_name` VARCHAR (40) NOT NULL , PRIMARY KEY (`cat_id`)) ENGINE = InnoDB; DEFAULT CHARSET=latin1 ;');
		$query->execute();
		$query = $this->connect()->pdo->prepare('CREATE TABLE IF NOT EXISTS `msg` (`msg_id` int(11) NOT NULL AUTO_INCREMENT, `msg_author` varchar(40) NOT NULL, `msg_date` date NOT NULL, `msg_time` datetime NOT NULL, `msg_msg` varchar(200) NOT NULL, PRIMARY KEY (`msg_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1');
		$query->execute();
		$count = $this->connect()->count("msg", [
		]);
		if ($count < 3){
			$this->connect()->insert("cat", [
				"cat_name" => 'Kategoria 1'
			]);
			$this->connect()->insert("cat", [
				"cat_name" => 'Kategoria 2'
			]);
			$this->connect()->insert("cat", [
				"cat_name" => 'Kategoria 3'
			]);
			$this->connect()->insert("msg", [
				"msg_msg" => 'To jest test wiadomosci',
				"msg_author" => 'Agata',
				"msg_date" => '22-07-20',
                "msg_time" => '22-07-20 12:22:50'
			]);
			$this->connect()->insert("msg", [
				"msg_msg" => 'To jest test wiadomosci',
				"msg_author" => 'Kasia',
				"msg_date" => '22-05-10',
                "msg_time" => '22-05-10 12:22:00'                
			]);
			$this->connect()->insert("msg", [
				"msg_msg" => 'To jest test wiadomosci',
				"msg_author" => 'Agnieszka',
				"msg_date" => '22-06-10',
                "msg_time" => '22-06-10 12:22:00'                
			]);
			$header = "Congratulations!";
			$msg = "Installing the framework has been completed successfully.";
		}
		else{
			$header = "Holy moly!";
			$msg = "Framework has already been installed.";
		}

        return $this->render($response, 'install.html', [
            'header' => $header,
            'msg' => $msg 
        ]);
    }


}
