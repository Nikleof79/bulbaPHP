<?php
class BulbaApp
{
    private $staticFolder;
    private $reqUrl;
    private $middlewares = [];
    // public function documentation()
    // {
    //     include "./docs.md";
    // }
    // NOTE: this function needs to redevelopement because it's showing docs very bad

    private function create_defeult_req()
    {
        

        return new BulbaAppReq();
    }


    private function create_defeult_res()
    {
        return new BulbaAppRes();
    }
    private function call_midlewares()
    {
        $req = $this->create_defeult_req();
        $res = $this->create_defeult_res();
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

    public function use($url, $param = null, $callback)
    {
        if (is_array($url) && is_callable($callback) && is_string($param)) {
            $this->middlewares[] = ['urls' => $url, 'param' => $param, 'function' => $callback];
        } else {
            throw new BadFunctionCallException("Not avaible paramerts for use function \n bulbaPHP", 228);
        }
    }

    public function req($url, $function)
    {
        if (is_string($url) && is_callable($function)) {
            if ($_SERVER['REQUEST_URI'] == $url) {
                $this->call_midlewares();
                $req = $this->create_defeult_req();
                $res = $this->create_defeult_res();
                $function($req, $res);
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
            throw new OverflowException('unable data');
        }
    }
    public function sendFile($x)
    {
        require_once($x);
        exit;
    }
    public function sendFileTxt($x)
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
            // public $method;
            public $body;

            public function __construct()
            {
                $this->url = $_SERVER['REQUEST_URI'];
                $this->ip = $_SERVER['REMOTE_ADDR'];
                // $this->method = $_SERVER['REQUEST_METHOD'];
                $this->body = $_REQUEST;
            }
        }