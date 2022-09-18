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

use App\Model\Audio;
use App\Model\Ban;
use App\Model\Heros;
use App\Model\Skin;
use App\Model\Tutorial;
use FilesystemIterator;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
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
            $raw["heroId"] = $item["heroId"];
            $raw["name"] = $item["name"];
            $raw["alias"] = $item["alias"];
            $raw["title"] = $item["title"];
            $raw["selectAudio"] = $item["selectAudio"];
            $raw["banAudio"] = $item["banAudio"];
            $raw["created_at"] = date("Y-m-d H:i:s");
            $raw["updated_at"] = date("Y-m-d H:i:s");
            array_push($all, $raw);

        }
        var_dump($all);

        $inserRes = Db::table("lol_heros")->insert($all);

    }


    public function file(\League\Flysystem\Filesystem $filesystem)
    {
        $heroMenu = new FilesystemIterator('public/audio');
        while ($heroMenu->valid()) { // 检测迭代器是否到底了
            echo $heroMenu->getFilename(), PHP_EOL;
            $name =  explode('-', $heroMenu->getFilename())[1];
            $herosInfo = Heros::where('title','=',$name)->first();
            $skinMenu = new FilesystemIterator('public/audio/'.$heroMenu->getFilename());
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

                $file = new FilesystemIterator('public/audio/'.$heroMenu->getFilename().'/'.$skinMenu->getFilename());

                while ($file->valid()) {
                    echo $file->getFilename(),PHP_EOL;
                    $audioUrl = 'audio/'.$heroMenu->getFilename().'/'. $skinMenu->getFilename().'/'.$file->getFilename();
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

    public function ban(\League\Flysystem\Filesystem $filesystem)
    {
        $heroMenu = new FilesystemIterator('public/ban');
        while ($heroMenu->valid()) { // 检测迭代器是否到底了
            echo $heroMenu->getFilename(), PHP_EOL;
            $name1 =  explode('】', $heroMenu->getFilename())[0];
            $name =  explode(' ', $name1)[1];

            $herosInfo = Heros::where('title','=',$name)->first();

            $audioUrl = 'ban/'.$heroMenu->getFilename();
            var_dump($audioUrl);
            $audioDara = [
                'audioUrl'=>$audioUrl,
                'heroId'=>$herosInfo['id'],
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            ];
            Ban::insert($audioDara);

            $heroMenu->next(); // 游标往后移动
        }
    }


    public function tutorial(\League\Flysystem\Filesystem $filesystem)
    {
        $heroMenu = new FilesystemIterator('public/tutorial');
        $i = 0;
        while ($heroMenu->valid()) { // 检测迭代器是否到底了
            echo $heroMenu->getFilename(), PHP_EOL;


            $audioUrl = 'tutorial/'.$heroMenu->getFilename();
//            var_dump($audioUrl);
//            $audioDara = [
//                'audioUrl'=>$audioUrl,
//                'created_at'=>date('Y-m-d H:i:s'),
//                'updated_at'=>date('Y-m-d H:i:s')
//            ];
//            $id = Tutorial::insertGetId($audioDara);
//             var_dump($id);
            echo $i++;
            $heroMenu->next(); // 游标往后移动
        }
    }
}