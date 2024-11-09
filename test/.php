<?php
include 'lib/bulba/bulbaPHP.php';
$app = new \Bulba\BulbaApp();

$app->setFreeFolders(['assets']);
$app->req('/','d',function(\Bulba\BulbaAppRes $req, \Bulba\BulbaAppRes $res){
    
});