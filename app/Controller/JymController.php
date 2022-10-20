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

namespace App\Controller;

use App\Model\GenShin\Hero;
use App\Model\LoL\Audio;
use App\Model\LoL\Ban;
use App\Model\LoL\Heros;
use App\Model\LoL\Map;
use App\Model\LoL\Skin;
use App\Model\LoL\Tutorial;
use FilesystemIterator;
use GuzzleHttp\Client;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class JymController extends AbstractController
{
    public function getGames(ResponseInterface $response)
    {

        $client = new Client();

        $res = $client->request('GET', 'https://game.gtimg.cn/images/lol/act/img/js/heroList/hero_list.js');
        $games = json_decode($res->getBody()->getContents(), true);
        var_dump($games);

        $data = $games["hero"];
        $all = [];
        foreach ($data as $key => $item) {
            $raw = [];
            $ban = [];
            $select = [];
            $raw["heroId"] = $item["heroId"];
            $raw["name"] = $item["name"];
            $raw["alias"] = $item["alias"];
            $raw["title"] = $item["title"];
            $raw["selectAudio"] = $item["selectAudio"];
            $raw["banAudio"] = $item["banAudio"];
            $raw["created_at"] = date("Y-m-d H:i:s");
            $raw["updated_at"] = date("Y-m-d H:i:s");
            $id = Heros::insertGetId($raw);

            $ban['heroId'] = $id;
            $ban['audioUrl'] =  $item['banAudio'];
            $ban['type'] = 0;
            $ban["created_at"] = date("Y-m-d H:i:s");
            $ban["updated_at"] = date("Y-m-d H:i:s");

            $select['heroId'] = $id;
            $select['audioUrl'] =  $item['selectAudio'];
            $select['type'] = 1;
            $select["created_at"] = date("Y-m-d H:i:s");
            $select["updated_at"] = date("Y-m-d H:i:s");
            Ban::insertGetId($ban);
            Ban::insertGetId($select);
        }


    }

    public function getban(){

    }


    public function file(\League\Flysystem\Filesystem $filesystem)
    {
        $heroMenu = new FilesystemIterator('public/lol/audio');
        while ($heroMenu->valid()) { // 检测迭代器是否到底了
            echo $heroMenu->getFilename(), PHP_EOL;
            $name =  explode('-', $heroMenu->getFilename())[1];
            $herosInfo = Heros::where('title','=',$name)->first();
            $skinMenu = new FilesystemIterator('public/lol/audio/'.$heroMenu->getFilename());
            while ($skinMenu->valid()) {
              echo  $skinMenu->getFilename(), PHP_EOL;;

                $skinName =  explode(' ', $skinMenu->getFilename())[0];
                $hasSetSkin = Skin::where('skin_name','=',$skinName)->first();

                if (empty($hasSetSkin)){
                    $skinData = [
                        'hero_id'=>$herosInfo['id'],
                        'skin_name'=>$skinName,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ];
                    var_dump($skinData);
                    $skinId = Skin::insertGetId($skinData);
                }else{
                    $skinId = $hasSetSkin['id'];
                }

                $file = new FilesystemIterator('public/lol/audio/'.$heroMenu->getFilename().'/'.$skinMenu->getFilename());

                while ($file->valid()) {
                    echo $file->getFilename(),PHP_EOL;
                    $audioUrl = 'lol/audio/'.$heroMenu->getFilename().'/'. $skinMenu->getFilename().'/'.$file->getFilename();
                    $audioDara = [
                        'url'=>$audioUrl,
                        'hero_id'=>$herosInfo['id'],
                        'skin_id'=>$skinId,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    ];
                    Audio::insert($audioDara);
                    $file->next(); // 游标往后移动
                }
                $skinMenu->next(); // 游标往后移动

            }
            $heroMenu->next(); // 游标往后移动
        }
    }




    public function tutorial(\League\Flysystem\Filesystem $filesystem)
    {
        $mapMenu = new FilesystemIterator('public/lol/map-audio');
        while ($mapMenu->valid()) { // 检测迭代器是否到底了
            echo $mapMenu->getFilename(), PHP_EOL;
            $map = [
                'map_name'=>$mapMenu->getFilename(),
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            $id = Map::insertGetId($map);
            $audioMenu = new FilesystemIterator('public/lol/map-audio/'.$mapMenu->getFilename());
            while ($audioMenu->valid()) {
                echo $audioMenu->getFilename(), PHP_EOL;

                $audioUrl = 'lol/map-audio/'.$audioMenu->getFilename();
                var_dump($audioUrl);
                $audioData = [
                    'map_id'=>$id,
                    'audioUrl'=>$audioUrl,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ];
                Tutorial::insert($audioData);
                $audioMenu->next(); // 游标往后移动

            } // 检测迭代器是否到底了

            $mapMenu->next(); // 游标往后移动
        }
    }

    public function toname()
    {
//        $url = 'lol/audio/drmundo-蒙多/base/1023757319--你好，你挂号了吗？.wav';
//        $last =  substr($url, strripos($url, "/"));
//        var_dump($last);
//        exit();
        $limit = 100;
        for ($i=1;$i<550;$i++){
           $list = Audio::take($limit)->skip(($i-1)*$limit)->get();
            foreach ($list as $item) {

                $prel = strripos($item['url'], "】");
                if ($prel){
                    $pre = substr($item['url'], $prel);
                    $last = $this->replace_specialChar(substr($pre, 0, strrpos($pre, "-")), ' ');
                }else{
                    $pre = substr($item['url'], strripos($item['url'], "--"));
                    $last = $this->replace_specialChar(substr($pre, 0, strrpos($pre, ".wav")), ' ');
                }
                var_dump($pre);
                var_dump($last);

                $update = ['name' => $last];
                Audio::where('id', '=', $item['id'])->update($update);
            }
            var_dump($i);
        }
    }
    function replace_specialChar($strParam){
        $regex = "/\/|\～|\【|\】|\『|\』|\：|\；|\《|\》|\’|\‘|\ |\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        return preg_replace($regex,"",$strParam);
    }

    public function add(){
        $limit = 100;
        for ($i=1;$i<550;$i++){
            $list = Tutorial::take($limit)->skip(($i-1)*$limit)->get();
            foreach ($list as $item) {
                $update = ['audioUrl' => '/'.$item['audioUrl']];
                Tutorial::where('id', '=', $item['id'])->update($update);
            }
            var_dump($i);
        }
    }

    public function ttoname()
    {
        $limit = 100;
        for ($i=1;$i<550;$i++){
            $list = Tutorial::take($limit)->skip(($i-1)*$limit)->get();
            foreach ($list as $item) {

                $prel = strripos($item['audioUrl'], "】");
                if ($prel){
                    $pre = substr($item['audioUrl'], $prel);
                    $last = $this->replace_specialChar(substr($pre, 0, strrpos($pre, "-")), ' ');
                }else{
                    $pre = substr($item['audioUrl'], strripos($item['audioUrl'], "--"));
                    $last = $this->replace_specialChar(substr($pre, 0, strrpos($pre, ".wav")), ' ');
                }
                var_dump($pre);
                var_dump($last);

                $update = ['name' => $last];
                Tutorial::where('id', '=', $item['id'])->update($update);
            }
            var_dump($i);
        }
    }

    public function getGSHero()
    {
        $url = 'https://api-static.mihoyo.com/common/blackboard/ys_obc/v1/home/content/list?app_sn=ys_obc&channel_id=189';
        $client = new Client();

        $res = $client->request('GET', $url);
        $games = json_decode($res->getBody()->getContents(), true);
        $juse = $games['data']['list'][0]['children'][0]['list'];
        foreach ($juse as $item){
            $data['name'] = $item['title'];
            $data['cover'] = $item['icon'];
            $data['content_id'] = $item['content_id'];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            \App\Model\GenShin\Heros::insert($data);
        }
    }

    public $data =[];
    public $url = '';
    public $desc ='';
    public $id ='';
    public $language ='';
    public function getGSHeroAudio()
    {
        $client = new Client();
        $all = \App\Model\GenShin\Heros::all();

        foreach ($all as $item){
            $this->id = $item['id'];
            var_dump('角色id=>'.$this->id);
            $url = 'https://api-static.mihoyo.com/common/blackboard/ys_obc/v1/content/info?app_sn=ys_obc&content_id='.$item['content_id'];
            var_dump($url);
            $res = $client->request('GET', $url);
            $games = json_decode($res->getBody()->getContents(), true);
            $juse = $games['data']['content']['contents'][2]['text'];
            $crawler = new Crawler();
            $crawler->addHtmlContent($juse);
//            $this->data = [];
            for ($i=1;$i<5;$i++){
                $this->language = $i;
            $crawler->filter('ul > li:nth-child(1) > table:nth-child(2) > tbody > tr')->reduce(function (Crawler $node, $i) {
                $title = $node->filter('td:nth-child(1)')->text();
                try {
                    $node->filter('td:nth-child(2)')->reduce(function (Crawler $td2, $i){
                        $src =  $td2->filter('source')->attr('src');
                        $text =  $td2->text();
                        $this->url = $src;
                        $this->desc = $text;
                    });


                    $insert = [
                        'audioUrl'=> $this->url,
                        'title'=>$title,
                        'desc'=>$this->desc,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                        'hero_id'=>$this->id,
                        'language_id'=>$this->language
                    ];
                    \App\Model\GenShin\Audio::insert($insert);
                }catch (\Exception $exception){
                    var_dump('第'.$i.'error');
                }

             });

            }
        }





    }
}