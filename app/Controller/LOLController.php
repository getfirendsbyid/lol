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
use App\Model\Games;
use App\Model\LoL\Heros;
use App\Request\HeroListRequest;
use App\Request\LOL\HeroInfoRequest;
use App\Utils\ApiResponseTrait;

class LOLController extends AbstractController
{
    use ApiResponseTrait;

    public function heroInfo(HeroInfoRequest $request)
    {
        var_dump($request->all());

        if (!$request->has(['id','page','limit'])){
            throw new BusinessException(HttpCode::LogicError,'参数不完整');
        }
        $request->validated();
        $id = $request->input('id');
        $skinId = $request->input('skinId');
        $page = $request->input('page');
        $limit = $request->input('limit');
        $data = Heros::getHeroInfo($id,$skinId,$page,$limit);
        return $this->responseSuccess('获取成功',$data);
    }

}
