<?php

class BulbaApp
{
    private $freefolders;
    private $reqUrl;
    private $middlewares = [];
    // public function documentation()
    // {
    //     include "./docs.md";
    // }
    // NOTE: this function needs to redevelopement because it's showing docs very bad
    function __construct(){
        $this->reqUrl = $_SERVER['REQUEST_URI'];
    }

    private function call_midlewares()
    {
        $req = new BulbaAppReq();
        $res = new BulbaAppRes();
        foreach ($this->middlewares as $key => $value) {
            if ($value['param'] == '*') {
                $value['function']($req, $res);
                break;
            } elseif ($value['param'] == '!') {
                $cond = false;
                foreach ($value['urls'] as $key => $value) {
                    if ($this->reqUrl != $value) {
                        $cond = true;
                        break;
                    }
                }
                if ($cond) {
                    $value['function']($req, $res);
                }
            } else {
                $cond = false;
                foreach ($value['urls'] as $key => $value) {
                    if ($this->reqUrl == $value) {
                        $cond = true;
                        break;
                    }
                }
                if ($cond) {
                    $value['function']($req, $res);
                }
            }
        }
    }

    public function use($url = [], $param = null, $callback)
    {
        if (is_array($url) && is_callable($callback) && is_string($param)) {
            $this->middlewares[] = ['urls' => $url, 'param' => $param, 'function' => $callback];
        } else {
            throw new BadFunctionCallException("Not avaible paramerts for 'use' function \n bulbaPHP", 228);
        }
    }

    public function session_init(){
        return function($req,$res) {
            session_start();
        };
    }

    public function setFreeFolders($folders){
        if (is_array($folders)) {
            foreach ($folders as $key => $value) {
                $this->freefolders[] = $value;
            }
        }else{
            throw new BadFunctionCallException("not expected parameters , expected an array - bulbaPHP");
        }
    }

    public function req($url,$param , $function)
    {
        if ($param == false && count(explode('',$param)) > 1) {
            $param = 'd';
        }
        if (is_string($url) && is_string($param) && is_callable($function)) {
            $this->call_midlewares();
                if ($param == 'd' || $param = '' || $param == ' ') {
                    if ($_SERVER['REQUEST_URI'] == $url) {
                        $req = new BulbaAppReq();
                        $res = new BulbaAppRes();
                        $function($req,$res);
                    }
                }else if($param == 'a'){
                    $url_splited = explode('/',$url);
                    $requst_url_splited = explode('/',$_SERVER['REQUEST_URI']);
                    $return_params = array();
                    if (count($url_splited) == count($requst_url_splited)) {
                        for ($i=0; $i < count($requst_url_splited); $i++) { 
                            if (count(explode(':',$url)) > 1) {
                                //when url has ":"
                                $return_params[explode(':',$url)[1]] = $requst_url_splited[$i];
                                $req = new BulbaAppReq($return_params);
                                $res = new BulbaAppRes();
                                $function($req,$res);
                            }
                        }
                    }
                }
                else{
                    throw new Exception("Not avaible parametr");
                }
        } else {
            throw new Exception("Not avaible paramerts");
        }
    }
}

class BulbaAppRes
{
    public function send($x)
    {
        echo $x;
        exit;
    }
    public function sendJson($x)
    {
        if (is_array($x)) {
            header('Content-Type: application/json');
            echo json_encode($x);
            exit;
        } else {
            throw new RuntimeException('unable data');
        }
    }
    public function include($x){
        include $x;
    }
    public function sendFile($x)
    {
        require_once $x;
        exit;
    }
    public function sendFileInner($x)
    {
        $text = file_get_contents($x);
        echo $text;
        exit;
    }
    public function render($x)
    {
        require_once('./views/' . $x . '.php');
        exit;
    }
    public function redirect($x){
        header('Location: ' . $x);
        exit;
    }
    public function header($type,$value){
        header($type .": " . $value);
    }
}

class BulbaAppReq
        {
            // private $url;
            // private $ip;
            // private $method;
            // private $body;

            // public function __get($name) {
            //     return $this->{$name};
            // }
            public $url;
            public $ip;
            public $body;
            public $files;
            public $param;
            public $session;

            public function __construct($params = null)
            {
                $this->files = $_FILES;
                // try {
                //     $this->session = $_SESSION;
                // } catch (Exception $th) {
                //     unset($this->session);
                // }
                if (isset($_SESSION) && $_SESSION != [] && $_SESSION != null) {
                    $this->session = $_SESSION;
                }else{
                    unset($this->session);
                }
                if (isset($params) && $params != null) {
                    $this->param = $params;
                }
                $this->url = $_SERVER['REQUEST_URI'];
                $this->ip = $_SERVER['REMOTE_ADDR'];
                $this->body = $_REQUEST;
            }
        }

// class BulbaAppMySql{
//     private $conn;
//     function __construct($url,$username,$password,$database) {
//         $conn = mysqli_connect($url,$username,$password,$database);
//     }
//     function query($sql_query){
//         return $this->conn->query($sql_query);
//     }
//     function queryAssoc($sql_query){
//         $result = $this->conn->query($sql_query);
//         return $result->fetch_assoc();
//     }
// }

