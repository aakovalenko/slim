<?php

require 'vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$container = $app->getContainer();

$container['db'] = function () {
    return new PDO('mysql:host=localhost;dbname=slim','andrii','1');
};

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

    $users = $this->db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_OBJ);
    var_dump($users);

    //return $this->view->render($response, 'home.twig');
})->setName('home');

$app->get('/users/{id}', function ($request, $response, $args) {

   /* $user = [
      'username' => 'Billy',
      'name' => 'B.Garett',
      'email' => 'bgarrett@codecourse.com'
    ];*/

    $user = [
       'id' => $args['id'],
        'username' => 'alex'
    ];

    var_dump(compact('user'));

    return $this->view->render($response, 'users.twig',[
        'users' => $user,
    ]);
})->setName('users.index');



$app->get('/contact', function ($request, $response) {
    return $this->view->render($response, 'contact.twig');
});

$app->get('/contact/confirm', function ($request, $response) {
    return $this->view->render($response, 'contact_confirm.twig');
});

$app->post('/contact', function ($request, $response) {

    return $response->withRedirect('/contact/confirm');

})->setName('contact');

$app->group('/topics', function () {
    $this->get('', function () {
        echo 'Topic list';
    });

    $this->get('/{id}', function ($request, $response, $args ) {
        echo 'Topic ' . $args['id'];
    });

    $this->post('', function () {
        echo 'Post topic';
    });
});




/*$app->post('/contact', function ($request, $response) {

  $params =  $request->getParam('email');
  var_dump($params);
  
})->setName('contact');*/

$app->run();