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

use App\Constants\HttpCode;
use App\Exception\BusinessException;
use App\Model\Audio;
use App\Model\Ban;
use App\Model\Games;
use App\Model\Heros;
use App\Model\Skin;
use App\Model\Tutorial;
use App\Request\DownloadRequest;
use App\Request\Games\HomeListRequest;
use App\Request\GetRadioListRequest;
use App\Request\HeroListRequest;
use App\Request\SkinListRequest;
use App\Request\TurorialListRequest;
use App\Utils\ApiResponseTrait;
use Hyperf\HttpServer\Contract\ResponseInterface;

class GamesController extends AbstractController
{
    use ApiResponseTrait;

    public function homeList(HomeListRequest $request)
    {
        $request->validated();
        $page = $request->input('page');
        $limit = $request->input('limit');
        var_dump($page);
        var_dump($limit);
        $all = Games::homeList($page,$limit);
        return $this->responseSuccess("获取成功",$all);
    }

}
