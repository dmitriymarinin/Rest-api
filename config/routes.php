<?php

use App\Controllers\MagazineController;
use App\Controllers\AuthorController;
use Pecee\SimpleRouter\SimpleRouter as Router;

Router::setDefaultNamespace('app\controllers');

Router::group([
    'prefix' => '/magazine',
], function () {
    Router::post('/add', [MagazineController::class, 'add']);
    Router::post('/update/{id}', [MagazineController::class, 'update'])
        ->where(['id' => '[\d]+']);
    Router::post('/delete/{id}', [MagazineController::class, 'delete'])
        ->where(['id' => '[\d]+']);
    Router::get('/list/', [MagazineController::class, 'list']);
});

Router::group([
    'prefix' => '/author',
], function () {
    Router::post('/add', [AuthorController::class, 'addAuthor']);
    Router::post('/update/{id}', [AuthorController::class, 'update'])
        ->where(['id' => '[\d]+']);
    Router::post('/delete/{id}', [AuthorController::class, 'delete'])
        ->where(['id' => '[\d]+']);
    Router::get('/list/', [AuthorController::class, 'list']);
});