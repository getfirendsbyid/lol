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

use App\Model\Games;
use App\Model\LoL\Heros;
use App\Request\HeroListRequest;
use App\Utils\ApiResponseTrait;

class GenShinController extends AbstractController
{
    use ApiResponseTrait;

    public function list(HeroListRequest $request)
    {
        $lolData = Heros::homeList();
        $data =[
            'lol'=>$lolData
        ];
        return $this->responseSuccess("获取成功",$data);
    }

}
