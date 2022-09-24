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
use App\Request\GetRadioListRequest;
use App\Request\HeroListRequest;
use App\Request\SkinListRequest;
use App\Request\TurorialListRequest;
use App\Utils\ApiResponseTrait;
use Hyperf\HttpServer\Contract\ResponseInterface;

class HomeController extends AbstractController
{
    use ApiResponseTrait;

    public function homeList(HeroListRequest $request)
    {
        $request->validated();

        $all = Games::list();
        return $this->responseSuccess("获取成功",$all);
    }

}
