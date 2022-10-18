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
}