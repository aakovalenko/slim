<?php

require 'vendor/autoload.php';

$app = new \Slim\App();

$container = $app->getContainer();

$container['greeting'] = function () {
    echo 'abc';
    return 'Hello from the container!';
};

$app->get('/', function(){
    echo $this->greeting.PHP_EOL;
    echo $this->greeting.PHP_EOL;
    echo $this->greeting.PHP_EOL;
});

$app->get('/users', function(){
    echo 'users';
});

$app->run();