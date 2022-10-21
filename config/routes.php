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
    return '12';
});

Router::addGroup('/test/',function (){
//    Router::get('start','App\Controller\JymController@getGames');
//    Router::get('file','App\Controller\JymController@file');
//    Router::get('ban','App\Controller\JymController@ban');
//    Router::get('tutorial','App\Controller\JymController@tutorial');
//    Router::get('toname','App\Controller\JymController@toname');
//    Router::get('add','App\Controller\JymController@add');
//    Router::get('ttoname','App\Controller\JymController@ttoname');

    //原神
//    Router::get('getGSHero','App\Controller\JymController@getGSHero');
    Router::get('getGSHeroAudio','App\Controller\JymController@getGSHeroAudio');

});

Router::addGroup('/api/home/',function (){
    Router::post('list','App\Controller\HomeController@list');
});

//lol路由
Router::addGroup('/api/lol/',function (){
    Router::post('list','App\Controller\LOLController@list');
    Router::post('heroInfo','App\Controller\LOLController@heroInfo');
});

//原神路由
Router::addGroup('/api/genShin/',function (){
    Router::post('getHeroList','App\Controller\GenShinController@getHeroList');
    Router::post('heroInfo','App\Controller\GenShinController@heroInfo');
});



//Router::addGroup('/lol/',function (){
//
//    Router::post('list','App\Controller\IndexController@list');
//    Router::post('getSkinList','App\Controller\IndexController@getSkinList');
//    Router::post('getAudioList','App\Controller\IndexController@getAudioList');
//    Router::post('getHeroInfo','App\Controller\IndexController@getHeroInfo');
//    Router::get('download','App\Controller\IndexController@download');
//    Router::post('banList','App\Controller\IndexController@banList');
//    Router::post('turorialList','App\Controller\IndexController@turorialList');
//});


Router::addGroup('/api/games/',function (){
    Router::post('homeList','App\Controller\GamesController@homeList');
});

