<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/lol/',function (){
//    Router::get('start','App\Controller\JymController@getGames');
//    Router::get('file','App\Controller\JymController@file');
//    Router::get('ban','App\Controller\JymController@ban');
    Router::get('tutorial','App\Controller\JymController@tutorial');
});

Router::addGroup('/hero/',function (){
    Router::post('list','App\Controller\IndexController@list');
    Router::post('getSkinList','App\Controller\IndexController@getSkinList');
    Router::post('getAudioList','App\Controller\IndexController@getAudioList');
    Router::post('getHeroInfo','App\Controller\IndexController@getHeroInfo');
    Router::get('download','App\Controller\IndexController@download');
    Router::post('banList','App\Controller\IndexController@banList');
    Router::post('turorialList','App\Controller\IndexController@turorialList');
});

