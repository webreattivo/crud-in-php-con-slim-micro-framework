<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->config([
    'templates.path' => './templates',
]);

$app->get('/', function () use ($app) {
    $app->render('index.phtml', ['id' => rand()]);
});

$app->get('/add', function () use ($app) {
    $app->render('add.phtml', []);

});

$app->get('/edit/:id', function ($id) use ($app) {
    $app->render('edit.phtml', []);
});

$app->get('/delete/:id', function ($id) {
});

$app->run();