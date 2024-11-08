# BulbaPHP guide

## Setup

copy files lib/bulba and .htaccess in your root project folder 

```php
//router.php
<?php
include ("lib/bulba/BulbaPHP.php");


$app = new \Bulba\BulbaApp();
```

## Routes

```php
//router.php
<?php
include ("lib/bulba/BulbaPHP.php");

$app = new \Bulba\BulbaApp();

$url = '/';
$param = 'd';

$app->req($url,$param, function(Bulba\BulbaAppReq $req,\Bulba\BulbaAppRes $res){
    // 
});
```

instead of ``` $url ``` you can use any url but it has to start with '/',
``` $param ``` is an argument that says how url will be parsed
and callback function has 2 required arguments : 
- ``` $res ``` - response class , has default functions like ``` send , sendFile ```
- ``` $req ``` - request class , has data about request like post from form and etc.

### Router parameters

``` $param ``` For ``` $app->req ``` it has 2 avaible values:
- ``` a ``` - advanced url parsing , allows you to use ``` '/account/:name' ``` as dynamic url and then get data from ``` $req->params['name'] ``` if you then you will see what did you use in url. Code <br> 
```php 
<?php
include ("lib/bulba/BulbaPHP.php");

$app = new \Bulba\BulbaApp();

$app->req("/account/:name","a",function($req,$res){ 
    $res->send( $req->params['name'] ); 
});
```
```bash
GET /account/alex -> RESPONSE 200 -> "alex" 
```

- ``` d ``` - default url parsing , it says url not will parse or something else , it will only check if request url equalse to ``` $url ```;

### Req/Res classes

- ``` \Bulba\BulbaAppReq  $req ``` 
  variables:
  - ``` $body ``` - body of post/get/put etc requestes
  - ``` $param ``` - value of advanced url parsing
  - ``` $ip ``` - ip of request
  - ``` $files ``` - files of post request if they was given
  - ``` $session ``` - if session was enabled it will got all session data
  - ``` $url ``` - url of request

- ``` \Bulba\BulbaAppRes  $res ```
  functions:
  - ``` send ``` - send something as request response
  - ``` sendJson ``` - sends data as Json usefull for APIs
  - ``` include ``` - includes something in response
  - ``` sendFile ``` - sends file as response
  - ``` sendFileInner ``` - sends body of file
  - ``` render ``` - renders .php page from folder /views/ , but can be used for .html files too
  - ``` redirect ``` - redirects request
  - ``` header ``` - sets headers of http response
  - ``` status ``` - sets status of http response

