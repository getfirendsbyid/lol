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
use App\Model\GenShin\Heros;
use App\Request\GenShin\GetHeroListRequest;
use App\Utils\ApiResponseTrait;

class GenShinController extends AbstractController
{
    use ApiResponseTrait;

    public function getHeroList(GetHeroListRequest $request)
    {
        if (!$request->has(['page','limit'])){
            throw new BusinessException(HttpCode::LogicError,'参数不完整');
        }
        $page = $request->input('page');
        $limit = $request->input('limit');
        $data = Heros::getHeroList($page,$limit);
        return $this->responseSuccess("获取成功",$data);
    }

    public function heroInfo(GetHeroListRequest $request)
    {
        if (!$request->has(['id'])){
            throw new BusinessException(HttpCode::LogicError,'参数不完整');
        }
        $id = $request->input('id');
        $data = Heros::Info($id);
        return $this->responseSuccess("获取成功",$data);
    }
}
