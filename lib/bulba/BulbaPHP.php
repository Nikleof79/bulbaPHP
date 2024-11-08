<?php

namespace Bulba;

use Closure;
class BulbaApp
{
    private $freefolders;
    private $reqUrl;
    private $middlewares = [];
    private $path = '.';
    function __construct()
    {
        $this->reqUrl = $_SERVER['REQUEST_URI'];
    }


    private function checkFreeFolder()
    {
        $ret_data = false;
        $url_exploded = explode('/', $this->reqUrl);
        foreach ($this->freefolders as $key => $value) {
            if ($url_exploded[1] == $value)
                $ret_data = true;
        }
        return $ret_data;
    }


        private function call_midleware($num)
    {
        $req = new BulbaAppReq();
        $res = new BulbaAppRes();
        $value = $this->middlewares[$num];
        if ($value['param'] == '*') {
            $value['function']($req, $res);
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

    public function use(array $url = [],string $param = null,Closure $callback)
    {
        if (is_array($url) && is_callable($callback) && is_string($param)) {
            $this->middlewares[] = ['urls' => $url, 'param' => $param, 'function' => $callback];
            $this->call_midleware(count($this->middlewares) - 1);
        } else {
            throw new \Exception("Not avaible paramerts for 'use' function \n bulbaPHP", 228);
        }
    }

    public function session_init()
    {
        return function ($req, $res) {
            session_start();
        };
    }

    public function setFreeFolders(array $folders)
    {
        if (is_array($folders)) {
            foreach ($folders as $key => $value) {
                $this->freefolders[] = $value;
            }
            if ($this->checkFreeFolder()) {
                $url_splited = explode('.',$this->reqUrl);
                $file_type = $url_splited[count($url_splited) - 1];
                $mimeTypes = [
                    'js' => 'application/javascript',
                    'html' => 'text/html',
                    'css' => 'text/css',
                    'json' => 'application/json',
                    'png' => 'image/png',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'pdf' => 'application/pdf',
                    'txt' => 'text/plain',
                    'xml' => 'application/xml',
                    'svg' => 'image/svg+xml',
                    'woff' => 'font/woff',
                    'woff2' => 'font/woff2',
                    'mp3' => 'audio/mpeg',
                    'mp4' => 'video/mp4'
                ];
                if (isset($mimeTypes[$file_type])) {
                    header("Content-type: {$file_type}");
                    include "{$this->path}{$this->reqUrl}";
                    exit; 
                }else{
                    http_response_code(404);
                }
            }
        } else {
            throw new \BadFunctionCallException("not expected parameters , expected an array - bulbaPHP");
        }
    }

    public function req(string $url,string $param,Closure $function)
    {
        if ($param == false && count(str_split($param)) > 1) {
            $param = 'd';
        }
        if (is_string($url) && is_string($param) && is_callable($function)) {
            if ($param == 'd' || $param == '' || $param == ' ') {
                if ($this->reqUrl == $url) {
                    $req = new BulbaAppReq();
                    $res = new BulbaAppRes();
                    $function($req, $res);
                }
            } else if ($param == 'a') {
                $url_splited = explode('/', $url);
                $requst_url_splited = explode('/', $this->reqUrl);
                $return_params = array();
                if (count($url_splited) == count($requst_url_splited)) {
                    for ($i = 0; $i < count($requst_url_splited); $i++) {
                        if (count(explode(':', $url_splited[$i])) > 1) {
                            //when url has ":"

                            $return_params[str_replace('/', '', explode(':', $url)[1])] = $requst_url_splited[$i];
                        }
                    }
                }
                $req = new BulbaAppReq($return_params);
                $res = new BulbaAppRes();
                $function($req, $res);
            } else {
                throw new \Exception("Not avaible parametr");
            }
        } else {
            throw new \Exception("Not avaible paramerts");
        }
    }
}

class BulbaAppRes
{
    public function send($x)
    {
        print_r($x);
        exit;
    }
    public function sendJson($x)
    {
        header('Content-Type: application/json');
        echo json_encode($x);
        exit;
    }
    public function include($x)
    {
        include $x;
        return $this;
    }
    public function sendFile($x)
    {
        $url_splited = explode('.',$x);
        $file_type = $url_splited[count($url_splited) - 1];
        $mimeTypes = [
            'js' => 'application/javascript',
            'html' => 'text/html',
            'css' => 'text/css',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'xml' => 'application/xml',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4'
        ];
        if (isset($mimeTypes[$file_type])){require_once $x;}
        else{
            http_response_code(404);
        }
        exit;
    }
    public function sendFileInner($x)
    {
        $text = file_get_contents($x);
        echo $text;
        exit;
    }
    public function render($x, $file_extension = '.php')
    {
        require_once("./views/{$x}.{$file_extension}");
        exit;
    }
    public function redirect($x)
    {
        header('Location: ' . $x);
        exit;
    }
    public function header($type, $value)
    {
        header("{$type}: {$value}");
        return $this;
    }

    public function status($status){
        http_response_code($status);
        return $this;
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
    public $cookie;
    public function __construct($params = null)
    {
        // try {
        //     $this->session = $_SESSION;
        // } catch (Exception $th) {
        //     unset($this->session);
        // }
        if (isset($_SESSION) && $_SESSION != [] && $_SESSION != null) {
            $this->session = $_SESSION;
        } else {
            unset($this->session);
        }
        if (isset($params) && $params != null) {
            $this->param = $params;
        }
        $this->url = $_SERVER['REQUEST_URI'];
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->body = $_REQUEST;
        $this->files = $_FILES;
        $this->cookie = $_COOKIE;
    }
}
