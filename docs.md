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