<?php
require 'vendor/autoload.php';

$db = new PDO('mysql:host=127.0.0.1;dbname=rubrica', 'root', 'rootpass');

$app = new \Slim\Slim();
$app->config([
    'templates.path' => './templates',
]);

$app->get('/', function () use ($app, $db) {
    $sql = $db->query('SELECT * FROM contacts ORDER BY id DESC');
    $sql->execute();
    $app->render('index.phtml', [
        'contacts' => $sql->fetchAll(PDO::FETCH_ASSOC)
    ]);
});

$app->map('/add', function () use ($app, $db) {
    if ($app->request->isPost()) {
        $sql = $db->prepare('INSERT INTO contacts (fullname, phone) VALUES (:fullname, :phone)');
        $sql->bindParam(':fullname', $app->request->post('fullname'));
        $sql->bindParam(':phone', $app->request->post('phone'));
        $sql->execute();
        $app->redirect('/');
    }
    $app->render('add.phtml', []);
})->via('GET', 'POST');

$app->get('/edit/:id', function ($id) use ($app, $db) {
    if ($app->request->isPost()) {
        $sql = $db->prepare('UPDATE contacts SET fullname = :fullname, phone = :phone WHERE id = :id');
        $sql->bindParam(':fullname', $app->request->post('fullname'));
        $sql->bindParam(':phone', $app->request->post('phone'));
        $sql->bindParam(':id', $app->request->post('id'));
        $sql->execute();
        $app->redirect('/');
    }
    $sql = $db->prepare('SELECT * FROM contacts WHERE id = :id LIMIT 0,1');
    $sql->bindParam(':id', $id);
    $sql->execute();
    $app->render('edit.phtml', [
        'contact' => $sql->fetch(PDO::FETCH_ASSOC)
    ]);
})->via('GET', 'POST');

$app->get('/delete/:id', function ($id) use ($app, $db) {
    $sql = $db->prepare('DELETE FROM contacts WHERE id = :id');
    $sql->bindParam(':id', $id);
    $sql->execute();
    $app->redirect('/');
});

$app->run();