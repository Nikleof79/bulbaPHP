# BulbaPhp guide

## Setup

copy files lib/bulba and .htaccess in your root project folder 

```php
//router.php
<?php
include ("../lib/bulba/BulbaPHP.php");

use Bulba\BulbaApp;

$app = new BulbaApp();
```