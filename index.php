<?php

require 'vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$container = $app->getContainer();

/*$container['greeting'] = function () {
    echo 'abc';
    return 'Hello from the container!';
};

$app->get('/', function(){
    echo $this->greeting.PHP_EOL;
    echo $this->greeting.PHP_EOL;
    echo $this->greeting.PHP_EOL;
});*/

/*$app->get('/users', function(){
    echo 'users';
});*/

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};


$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'home.twig');
})->setName('home');

$app->get('/users', function ($request, $response) {

   /* $user = [
      'username' => 'Billy',
      'name' => 'B.Garett',
      'email' => 'bgarrett@codecourse.com'
    ];*/

    $users = [
        ['username' => 'alex'],
        ['username' => 'billy'],
        ['username' => 'dale'],
    ];

    return $this->view->render($response, 'users.twig',[
        'users' => $users,
    ]);
})->setName('users.index');

$app->get('/contact', function ($request, $response) {
    return $this->view->render($response, 'contact.twig');
});

$app->post('/contact', function ($request, $response) {

    die('Contact!!!');
})->setName('contact');

$app->run();