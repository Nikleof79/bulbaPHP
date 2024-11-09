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

## Midlewares
midleware creates with function ``` use ``` of ``` BulbaApp ``` objects
```php 
<?php
include ("../lib/bulba/BulbaPHP.php");

$app = new \Bulba\BulbaApp();

$app->use(['/url1','/url2'],"",function(\Bulba\BulbaAppReq $req, \Bulba\BulbaAppRes $res){ 
    $res->status(200);
});
```

its example of easy midleware that will set http response code to 200 if url equals to ``` url1 ``` or ``` url2 ``` , here parameters like in default request handler:
```php
$app->use($urls , $param , function(\Bulba\BulbaAppReq $req, \Bulba\BulbaAppRes $res){});
```

- ``` $urls ``` - array of urls
- ``` $param ``` - can use as value only 3 values:
  - ``` ! ``` - middleware will run if url of request url not equals to any of ``` $urls ```
  - ``` d ``` - default , middleware will run request url equals to any of ``` $urls ```
  - ``` * ``` - will run middleware for every request

## FreeFolders

freefolder - its a folder when user can get EVERY file usefull for folder with css/images/js and etc

```php
<?php 

include ("../lib/bulba/BulbaPHP.php");

$app = new \Bulba\BulbaApp();

$app->setFreeFolders(["assets","handlers"]);
```

this code will set ``` assets ``` as free folder , if you want to add freefolder it has to don't start with ``` / ``` , ``` ./ ``` and etc , only folders in root directory of project . And directory will be without more directories like ``` assets/css ``` 