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
use App\Request\Games\HomeListRequest;
use App\Utils\ApiResponseTrait;

class GamesController extends AbstractController
{
    use ApiResponseTrait;

    public function homeList(HomeListRequest $request)
    {
        $request->validated();
        $limit = $request->input('limit');
        $all = Games::homeList($limit);
        return $this->responseSuccess("获取成功",$all);
    }

}
