# Start With BulbaPHP
## including
```php 
<?php
include "./bulba.php";
$app = new BulbaApp;
```
## first handler
```php
    $app->req('/', function($req,$res) { <br>
        $res->send('Hello bulbaPHP'); <br>
    });
```

## send pages

```php
    $app->req('/',function($req,$res) {
        $res->sendFile('index.php');
    })
```
### instead of sendFile can be used render it will render all by path "./views"

```php 
    $app->req('/',function($req,$res){
        $res->render('index');
    })
```
### if project architecture looks like:
```
├── .htaccess
├── router.php
├── bulbaPHP.php
└── views
    └── index.php
```


## this construction can be used like post/get/put/delete etc queries
```php
    $app->req('/',function($req,$res){
        //code
    })
```

## session handler

```php
$app->use([],'*',session_init()); // this code will initialize session

$app->req('/',function($req,$res){
    print_r($req.session); // will print all in session
})
```

## middleware
In bulbaPHP middleware is middle program between sending page and handling request 
──>Request──>Middleware──>Request handler──>Rendering──>👍
```php 
$app->use(['/urls'],function($req,$res){
//code
});
```
- first parametr is an array of urls for middleware, if request is on url from array middleware will be started.
- second parametr will be an argument:
    - '!' middleware will handle all requests not by url/urls
    - '*' middleware will handle all requests
    - '' middleware will work normal👍
- third parametr is function, its function like in request handler

## paramameters 

<!-- 
## for work with MYSQL we can use BulbaAppMySql
```php
    $mysql = new BulbaAppMySql($url,$username,$password,$database)
```

## use Mysql for get array
```php 
$request = $mysql->QueryAssoc($sql_query);
```

### Or get mysqli_result
```php 
$request = $mysql->query($sql_query);
``` -->
